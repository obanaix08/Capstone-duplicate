import { Card, Row, Col, Table, Badge } from 'react-bootstrap'
import { useEffect, useState } from 'react'
import axios from 'axios'
import { Line, Bar } from 'react-chartjs-2'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, Tooltip, Legend } from 'chart.js'
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Tooltip, Legend)

export default function Forecasting() {
  const [materials, setMaterials] = useState([])
  const [salesForecast, setSalesForecast] = useState({ labels: [], datasets: [] })
  const [capacity, setCapacity] = useState({ labels: [], datasets: [] })

  useEffect(() => {
    axios.get('/api/forecasting/overview')
      .then(res => {
        const { materials: mats, salesForecast: sf, capacity: cap } = res.data
        setMaterials(mats)
        setSalesForecast({ labels: sf.labels, datasets: [{ label: 'Sales', data: sf.data, backgroundColor:'#8B5E3C' }] })
        setCapacity({ labels: cap.labels, datasets: [{ label: '% Utilization', data: cap.data, backgroundColor:'#6c757d' }] })
      })
  }, [])

  return (
    <>
      <Row className="mb-3">
        <Col md={8}><Card><Card.Header>Material Depletion Forecast</Card.Header><Table size="sm" className="mb-0"><thead><tr><th>Code</th><th>Name</th><th>Stock</th><th>Avg Daily Use</th><th>Days Left</th><th>Suggested Reorder</th></tr></thead><tbody>{materials.map(m => (<tr key={m.id}><td>{m.code}</td><td>{m.name}</td><td>{m.stock}</td><td>{m.avg_daily_consumption}</td><td><Badge bg={m.predicted_days_until_depletion<=7?'danger':(m.predicted_days_until_depletion<=14?'warning':'success')}>{m.predicted_days_until_depletion}</Badge></td><td>{m.suggested_reorder_qty}</td></tr>))}</tbody></Table></Card></Col>
        <Col md={4}><Card><Card.Header>Sales Forecast (3 Months)</Card.Header><Card.Body><Bar data={salesForecast} /></Card.Body></Card></Col>
      </Row>
      <Row className="mb-3">
        <Col md={8}><Card><Card.Header>Material Replenishment Forecast</Card.Header><Table size="sm" className="mb-0"><thead><tr><th>Material</th><th>Current</th><th>Suggested Reorder</th></tr></thead><tbody>{replTable.map((r,i)=>(<tr key={i}><td>{r.material}</td><td>{r.current}</td><td>{r.reorder}</td></tr>))}</tbody></Table></Card></Col>
        <Col md={4}><Card><Card.Header>Production Capacity Planning</Card.Header><Card.Body><Bar data={capacity} /></Card.Body></Card></Col>
      </Row>
    </>
  )
}

