import { Card, Form, Button } from 'react-bootstrap'

export default function Settings() {
  return (
    <Card>
      <Card.Header>Settings</Card.Header>
      <Card.Body>
        <Form className="mb-3">
          <Form.Group className="mb-2">
            <Form.Label>Company Name</Form.Label>
            <Form.Control defaultValue="Unick Enterprises Inc." />
          </Form.Group>
          <Button>Save</Button>
        </Form>
      </Card.Body>
    </Card>
  )
}

