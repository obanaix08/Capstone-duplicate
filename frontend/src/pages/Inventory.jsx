import { Card, Table, Button, Badge } from 'react-bootstrap'

export default function Inventory() {
  const rows = Array.from({ length: 8 }).map((_, i) => ({
    sku: `SKU-${1000 + i}`,
    name: `Product ${i + 1}`,
    stock: Math.floor(Math.random() * 100),
    low: 20,
  }))
  return (
    <Card>
      <Card.Header>Inventory Management</Card.Header>
      <Table hover responsive className="mb-0">
        <thead>
          <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Stock</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {rows.map(r => (
            <tr key={r.sku}>
              <td>{r.sku}</td>
              <td>{r.name}</td>
              <td>{r.stock}</td>
              <td>{r.stock <= r.low ? <Badge bg="danger">Low</Badge> : <Badge bg="success">OK</Badge>}</td>
              <td className="text-end"><Button size="sm">Edit</Button></td>
            </tr>
          ))}
        </tbody>
      </Table>
    </Card>
  )
}

