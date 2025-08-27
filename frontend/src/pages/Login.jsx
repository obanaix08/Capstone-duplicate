import { Card, Form, Button } from 'react-bootstrap'

export default function Login() {
  return (
    <div className="d-flex justify-content-center align-items-center" style={{minHeight:'70vh'}}>
      <Card style={{maxWidth:420, width:'100%'}}>
        <Card.Body>
          <h4 className="mb-3">Sign In</h4>
          <Form>
            <Form.Group className="mb-2">
              <Form.Label>Email</Form.Label>
              <Form.Control type="email" placeholder="you@example.com" />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Password</Form.Label>
              <Form.Control type="password" placeholder="••••••••" />
            </Form.Group>
            <Button className="w-100">Login</Button>
          </Form>
        </Card.Body>
      </Card>
    </div>
  )
}

