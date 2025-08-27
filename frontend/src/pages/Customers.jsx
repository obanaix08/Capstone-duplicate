import { Card, Table, Button } from 'react-bootstrap'

export default function Customers() {
  return (
    <Card>
      <Card.Header>Customer Management</Card.Header>
      <Table hover responsive className="mb-0">
        <thead>
          <tr><th>Name</th><th>Email</th><th>Phone</th><th></th></tr>
        </thead>
        <tbody>
          {Array.from({length:8}).map((_,i)=>(
            <tr key={i}><td>Customer {i+1}</td><td>customer{i+1}@mail.com</td><td>09{i}1234567</td><td className="text-end"><Button size="sm">View</Button></td></tr>
          ))}
        </tbody>
      </Table>
    </Card>
  )
}

