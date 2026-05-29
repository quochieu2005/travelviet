import { useState } from 'react'

import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'

import Nav from './Components/Nav/Nav'
import Index from './Components/Page/Index'
import TourDetailPage from './Components/Page/TourDetail'
import CartPage from './Components/Page/Cart'

function App() {


  return (
    <>
      <Router>
        <Nav />
        <Routes>
          <Route path = '/' element = { <Index /> } />
          <Route path = '/TourDetail/:slug' element = { <TourDetailPage />} />
          <Route path = '/cart' element = { <CartPage />} />
        </Routes>
      </Router>
    </>
  )
}

export default App
