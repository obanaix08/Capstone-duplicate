import { Row, Col, Card, Table, Badge } from 'react-bootstrap'
import { useEffect, useState } from 'react'
import axios from 'axios'
import { Bar, Line, Pie } from 'react-chartjs-2'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  ArcElement,
  Tooltip,
  Legend,
} from 'chart.js'
ChartJS.register(CategoryScale, LinearScale, BarElement, PointElement, LineElement, ArcElement, Tooltip, Legend)

const card = (title, value, variant = 'primary') => (
  <Card className="mb-3">
    <Card.Body>
      <div className="text-muted small">{title}</div>
      <div className="fs-3 fw-semibold text-{variant}">{value}</div>
    </Card.Body>
  </Card>
)

export default function Dashboard() {
  const [lowStock, setLowStock] = useState({ products: [], materials: [] })
  const [forecast, setForecast] = useState({ alerts: [] })
  const [overview, setOverview] = useState({ total_orders: 0, active_orders: 0, inventory_items: 0, customers: 0, recent_orders: [] })
  const [charts, setCharts] = useState({ months: [], production: [], sales: [], topSelling: [] })

  useEffect(() => {
    axios.get('/api/inventory/low-stock').then(res => setLowStock(res.data))
    axios.get('/api/forecasting/overview').then(res => setForecast(res.data))
    axios.get('/api/dashboard/overview').then(res => setOverview(res.data))
    axios.get('/api/dashboard/charts').then(res => setCharts(res.data))
  }, [])
  const barData = { labels: charts.months, datasets: [{ label: 'Production', data: charts.production, backgroundColor: '#8B5E3C' }] }
  const lineData = { labels: charts.months, datasets: [{ label: 'Sales', data: charts.sales, borderColor: '#0d6efd' }] }
  const pieData = { labels: charts.topSelling.map(t => `#${t.product_id}`), datasets: [{ data: charts.topSelling.map(t => t.qty), backgroundColor: ['#8B5E3C','#C69C6D','#5C4033','#A67B5B','#D2B48C'] }] }

  return (
    <>
      <Row>
        <Col md={3}>{card('Total Orders', overview.total_orders)}</Col>
        <Col md={3}>{card('Active Orders', overview.active_orders)}</Col>
        <Col md={3}>{card('Inventory Items', overview.inventory_items)}</Col>
        <Col md={3}>{card('Customers', overview.customers)}</Col>
      </Row>

      <Row className="mb-3">
        <Col md={8} className="mb-3">
          <Card>
            <Card.Header>Monthly Production Output</Card.Header>
            <Card.Body>
              <Bar data={barData} />
            </Card.Body>
          </Card>
        </Col>
        <Col md={4} className="mb-3">
          <Card>
            <Card.Header>Top Selling Products</Card.Header>
            <Card.Body>
              <Pie data={pieData} />
            </Card.Body>
          </Card>
        </Col>
      </Row>

      <Row className="mb-3">
        <Col>
          <Card>
            <Card.Header>Sales Trend</Card.Header>
            <Card.Body>
              <Line data={lineData} />
            </Card.Body>
          </Card>
        </Col>
      </Row>

      <Row>
        <Col md={8} className="mb-3">
          <Card>
            <Card.Header>Recent Orders</Card.Header>
            <Table striped hover responsive className="mb-0">
              <thead>
                <tr><th>Customer</th><th>Amount</th><th>Date</th><th>Status</th></tr>
              </thead>
              <tbody>
                {overview.recent_orders.map((o)=> (
                  <tr key={o.id}><td>{o.customer?.name || 'N/A'}</td><td>₱{Number(o.total_amount).toFixed(2)}</td><td>{new Date(o.ordered_at || o.created_at).toLocaleDateString()}</td><td>{o.status}</td></tr>
                ))}
              </tbody>
            </Table>
          </Card>
        </Col>
        <Col md={4} className="mb-3">
          <Card>
            <Card.Header>Low Stock Alerts</Card.Header>
            <Table size="sm" className="mb-0">
              <tbody>
                {lowStock.materials.slice(0,5).map((m)=> (
                  <tr key={m.id}><td>{m.name}</td><td><Badge bg="danger">Low</Badge></td></tr>
                ))}
              </tbody>
            </Table>
          </Card>
        </Col>
      </Row>
      <Row>
        <Col>
          <Card>
            <Card.Header>Upcoming Replenishment Alerts (<= 7 days)</Card.Header>
            <Table size="sm" className="mb-0">
              <thead><tr><th>Material</th><th>Days Left</th><th>Suggested Reorder</th></tr></thead>
              <tbody>
                {forecast.alerts?.map((a)=> (
                  <tr key={a.id}><td>{a.name}</td><td><Badge bg={a.predicted_days_until_depletion<=3?'danger':'warning'}>{a.predicted_days_until_depletion}</Badge></td><td>{a.suggested_reorder_qty}</td></tr>
                ))}
              </tbody>
            </Table>
          </Card>
        </Col>
      </Row>
    </>
  )
}

