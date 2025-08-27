import { Card, Table, Badge, Form, Spinner } from 'react-bootstrap'
import { useEffect, useState } from 'react'
import axios from 'axios'

export default function Orders() {
  const [status, setStatus] = useState('all')
  const [rows, setRows] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const params = {}
    if (status !== 'all') params.status = status
    setLoading(true)
    axios.get('/api/orders', { params }).then(res => {
      const data = res.data.data || []
      setRows(data.map(o => ({
        id: o.id,
        customer: o.customer?.name || 'N/A',
        date: new Date(o.ordered_at || o.created_at).toLocaleDateString(),
        amount: Number(o.total_amount).toFixed(2),
        status: o.status,
      })))
    }).finally(() => setLoading(false))
  }, [status])

  const filtered = rows
  return (
    <Card>
      <Card.Header className="d-flex justify-content-between align-items-center">
        <div>Order Management</div>
        <Form.Select onChange={e => setStatus(e.target.value)} value={status} style={{width:220}} size="sm">
          <option value="all">All statuses</option>
          <option value="pending">Pending</option>
          <option value="in_production">In Production</option>
          <option value="delivered">Delivered</option>
        </Form.Select>
      </Card.Header>
      <Table responsive hover className="mb-0">
        <thead>
          <tr><th>#</th><th>Customer</th><th>Date</th><th>Amount</th><th>Status</th></tr>
        </thead>
        <tbody>
          {loading ? (
            <tr><td colSpan={5} className="text-center"><Spinner size="sm" /> Loading...</td></tr>
          ) : filtered.length === 0 ? (
            <tr><td colSpan={5} className="text-center">No orders</td></tr>
          ) : filtered.map(r => (
            <tr key={r.id}>
              <td>{r.id}</td>
              <td>{r.customer}</td>
              <td>{r.date}</td>
              <td>₱{r.amount}</td>
              <td>{r.status === 'delivered' ? <Badge bg="success">Delivered</Badge> : r.status === 'in_production' ? <Badge bg="warning">In Production</Badge> : <Badge bg="secondary">Pending</Badge>}</td>
            </tr>
          ))}
        </tbody>
      </Table>
    </Card>
  )
}

