import { Card, Table, ProgressBar, Button } from 'react-bootstrap'

export default function Productions() {
  const rows = Array.from({ length: 6 }).map((_, i) => ({
    batch: `BATCH-${(Math.random()*1e5|0).toString(36).toUpperCase()}`,
    product: `Product ${i + 1}`,
    qty: Math.floor(Math.random() * 50) + 10,
    progress: Math.floor(Math.random() * 100),
    eta: new Date(Date.now() + Math.random() * 1e9).toLocaleDateString(),
  }))
  return (
    <Card>
      <Card.Header>Production Tracking</Card.Header>
      <Table responsive hover className="mb-0">
        <thead>
          <tr><th>Batch</th><th>Product</th><th>Qty</th><th>Progress</th><th>ETA</th><th></th></tr>
        </thead>
        <tbody>
          {rows.map((r, idx) => (
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

