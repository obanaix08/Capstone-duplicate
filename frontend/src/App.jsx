import './App.css'
import { Container, Nav, Navbar } from 'react-bootstrap'
import { Routes, Route, Link, Navigate } from 'react-router-dom'
import Dashboard from './pages/Dashboard'
import Inventory from './pages/Inventory'
import Productions from './pages/Productions'
import Orders from './pages/Orders'
import Reports from './pages/Reports'
import Forecasting from './pages/Forecasting'
import Customers from './pages/Customers'
import Settings from './pages/Settings'
import Login from './pages/Login'

function App() {
  return (
    <div className="d-flex">
      <div className="sidebar-wood border-end" style={{ width: 240, minHeight: '100vh' }}>
        <div className="p-3 fw-bold">Unick OPS</div>
        <Nav className="flex-column px-2">
          <Nav.Link as={Link} to="/">Dashboard</Nav.Link>
          <Nav.Link as={Link} to="/inventory">Inventory</Nav.Link>
          <Nav.Link as={Link} to="/production">Production</Nav.Link>
          <Nav.Link as={Link} to="/orders">Orders</Nav.Link>
          <Nav.Link as={Link} to="/reports">Reports</Nav.Link>
          <Nav.Link as={Link} to="/forecasting">Forecasting</Nav.Link>
          <Nav.Link as={Link} to="/customers">Customers</Nav.Link>
          <Nav.Link as={Link} to="/settings">Settings</Nav.Link>
        </Nav>
      </div>
      <div className="flex-grow-1">
        <Navbar className="wood-nav">
          <Container>
            <Navbar.Brand>Operations Dashboard</Navbar.Brand>
            <Nav>
              <Nav.Link as={Link} to="/login">Sign In</Nav.Link>
            </Nav>
          </Container>
        </Navbar>
        <Container className="py-4">
          <Routes>
            <Route path="/" element={<Dashboard />} />
            <Route path="/inventory" element={<Inventory />} />
            <Route path="/production" element={<Productions />} />
            <Route path="/orders" element={<Orders />} />
            <Route path="/reports" element={<Reports />} />
            <Route path="/forecasting" element={<Forecasting />} />
            <Route path="/customers" element={<Customers />} />
            <Route path="/settings" element={<Settings />} />
            <Route path="/login" element={<Login />} />
            <Route path="*" element={<Navigate to="/" replace />} />
          </Routes>
        </Container>
      </div>
    </div>
  )
}

export default App
