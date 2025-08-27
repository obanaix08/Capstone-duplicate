import { Card, Table, ProgressBar, Button, Spinner } from 'react-bootstrap'
import { useEffect, useState } from 'react'
import axios from 'axios'

export default function Productions() {
  const [rows, setRows] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    setLoading(true)
    axios.get('/api/productions').then(res => {
      const data = res.data.data || []
      setRows(data.map(p => ({
        id: p.id,
        batch: p.batch_number,
        product: p.product?.name || 'N/A',
        qty: p.quantity,
        progress: p.progress_percent,
        eta: p.estimated_completion_date ? new Date(p.estimated_completion_date).toLocaleDateString() : '-',
      })))
    }).finally(() => setLoading(false))
  }, [])
  return (
    <Card className="wood-card">
      <Card.Header className="wood-header">Production Tracking</Card.Header>
      <Table responsive hover className="mb-0">
        <thead>
          <tr><th>Batch</th><th>Product</th><th>Qty</th><th>Progress</th><th>ETA</th><th></th></tr>
        </thead>
        <tbody>
          {loading ? (
            <tr><td colSpan={6} className="text-center"><Spinner size="sm" /> Loading...</td></tr>
          ) : rows.length === 0 ? (
            <tr><td colSpan={6} className="text-center">No productions</td></tr>
          ) : rows.map((r, idx) => (
            <tr key={idx}>
              <td>{r.batch}</td>
              <td>{r.product}</td>
              <td>{r.qty}</td>
              <td style={{width:240}}><ProgressBar now={r.progress} label={`${r.progress}%`} /></td>
              <td>{r.eta}</td>
              <td className="text-end"><Button size="sm">View</Button></td>
            </tr>
          ))}
        </tbody>
      </Table>
    </Card>
  )
}

