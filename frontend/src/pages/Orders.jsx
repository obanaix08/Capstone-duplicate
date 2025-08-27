import { Card, Table, Badge, Form } from 'react-bootstrap'
import { useState } from 'react'

export default function Orders() {
  const [status, setStatus] = useState('all')
  const rows = Array.from({ length: 10 }).map((_, i) => ({
    id: 1000 + i,
    customer: `Customer ${i + 1}`,
    date: new Date().toLocaleDateString(),
    amount: (Math.random() * 50000 + 2000).toFixed(2),
    status: ['pending','in_production','delivered'][i % 3],
  }))
  const filtered = status === 'all' ? rows : rows.filter(r => r.status === status)
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
          {filtered.map(r => (
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

