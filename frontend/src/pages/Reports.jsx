import { Card, Tabs, Tab, Table } from 'react-bootstrap'

export default function Reports() {
  return (
    <Card>
      <Card.Header>Reports</Card.Header>
      <Card.Body>
        <Tabs defaultActiveKey="sales">
          <Tab eventKey="sales" title="Sales">
            <Table size="sm"><tbody><tr><td>Daily Sales</td><td>₱ 120,000</td></tr></tbody></Table>
          </Tab>
          <Tab eventKey="inventory" title="Inventory">
            <Table size="sm"><tbody><tr><td>Low Stock Items</td><td>12</td></tr></tbody></Table>
          </Tab>
          <Tab eventKey="production" title="Production">
            <Table size="sm"><tbody><tr><td>Completed Batches</td><td>8</td></tr></tbody></Table>
          </Tab>
          <Tab eventKey="performance" title="Performance">
            <Table size="sm"><tbody><tr><td>On-time Rate</td><td>92%</td></tr></tbody></Table>
          </Tab>
        </Tabs>
      </Card.Body>
    </Card>
  )
}

