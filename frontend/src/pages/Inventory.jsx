import { Card, Table, Button, Badge, Spinner } from 'react-bootstrap'
import { useEffect, useState } from 'react'
import axios from 'axios'

export default function Inventory() {
  const [rows, setRows] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    setLoading(true)
    axios.get('/api/inventory').then(res => {
      const products = res.data.products?.data || []
      setRows(products.map(p => ({
        sku: p.sku,
        name: p.name,
        stock: p.stock,
        low: p.low_stock_threshold,
      })))
    }).finally(() => setLoading(false))
  }, [])

  return (
    <Card className="wood-card">
      <Card.Header className="wood-header">Inventory Management</Card.Header>
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
          {loading ? (
            <tr><td colSpan={5} className="text-center"><Spinner size="sm" /> Loading...</td></tr>
          ) : rows.length === 0 ? (
            <tr><td colSpan={5} className="text-center">No items</td></tr>
          ) : rows.map(r => (
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

