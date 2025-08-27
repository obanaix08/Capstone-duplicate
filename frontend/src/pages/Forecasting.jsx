import { Card, Row, Col, Table } from 'react-bootstrap'
import { Line, Bar } from 'react-chartjs-2'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, Tooltip, Legend } from 'chart.js'
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Tooltip, Legend)

export default function Forecasting() {
  const months = ['This Mo','Next Mo','+2 Mo']
  const inventoryForecast = { labels: Array.from({length:12}).map((_,i)=>`Week ${i+1}`), datasets: [{ label: 'Inventory', data: Array.from({length:12}).map(()=>Math.floor(Math.random()*100)), borderColor:'#0d6efd'}]}
  const salesForecast = { labels: months, datasets: [{ label: 'Sales', data: [80,95,110], backgroundColor:'#8B5E3C' }]}
  const capacity = { labels: ['Plant A','Plant B','Plant C'], datasets: [{ label: '% Utilization', data: [72,68,81], backgroundColor:'#6c757d' }]}
  const replTable = Array.from({length:5}).map((_,i)=>({material:`Material ${i+1}`, current:Math.floor(Math.random()*50), reorder: Math.floor(Math.random()*30)+10}))

  return (
    <>
      <Row className="mb-3">
        <Col md={8}><Card><Card.Header>Inventory Forecast</Card.Header><Card.Body><Line data={inventoryForecast} /></Card.Body></Card></Col>
        <Col md={4}><Card><Card.Header>Sales Forecast (3 Months)</Card.Header><Card.Body><Bar data={salesForecast} /></Card.Body></Card></Col>
      </Row>
      <Row className="mb-3">
        <Col md={8}><Card><Card.Header>Material Replenishment Forecast</Card.Header><Table size="sm" className="mb-0"><thead><tr><th>Material</th><th>Current</th><th>Suggested Reorder</th></tr></thead><tbody>{replTable.map((r,i)=>(<tr key={i}><td>{r.material}</td><td>{r.current}</td><td>{r.reorder}</td></tr>))}</tbody></Table></Card></Col>
        <Col md={4}><Card><Card.Header>Production Capacity Planning</Card.Header><Card.Body><Bar data={capacity} /></Card.Body></Card></Col>
      </Row>
    </>
  )
}

