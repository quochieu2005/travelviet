import { useState } from 'react'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import { AuthProvider } from './context/AuthContext' // Thêm import AuthProvider

import Nav from './Components/Nav/Nav'
import Index from './Components/Page/Index'
import TourDetailPage from './Components/Page/TourDetail'
import CartPage from './Components/Page/Cart'
import BookingComfirmation from './Components/Page/TourBookingSummery'
import Footer from './Components/Layout/Footer'
import Tours from './Components/Page/Tours'
import Hotels from './Components/Page/Hotels'
import Transport from './Components/Page/Transport'
import Restaurants from './Components/Page/Restaurants'
import About from './Components/Page/About'
import BlogSection from './Components/Page/Blog'
import ContactSection from './Components/Page/Contact'
import Login from './Components/Page/Login'
import Register from './Components/Page/Register'
import Profile from './Components/Page/Profile'

function App() {
  return (
    <AuthProvider> 
      <Router>
        <Nav />
        <Routes>
          <Route path='/' element={<Index />} />
          <Route path='/TourDetail/:slug' element={<TourDetailPage />} />
          <Route path='/cart' element={<CartPage />} />
          <Route path='/booking-confirmation' element={<BookingComfirmation />} />
          <Route path='/Tours' element={<Tours />} />
          <Route path='/Hotels' element={<Hotels />} />
          <Route path='/Transports' element={<Transport />} />
          <Route path='/Restaurants' element={<Restaurants />} />
          <Route path='/About' element={<About />} />
          <Route path='/Blog' element={<BlogSection />} />
          <Route path='/Contact' element={<ContactSection />} />
          <Route path='/Login' element={<Login />} />
          <Route path='/Register' element={<Register />} />
          <Route path='/profile' element={<Profile />} />
        </Routes>
        <Footer />
      </Router>
    </AuthProvider>
  )
}

export default App