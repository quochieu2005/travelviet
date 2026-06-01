import React, { useContext, useState } from 'react'
import { CartContext } from '../../context/CartContext'
import restaurantData from '../../data/Restaurant.json'
import { Link } from 'react-router-dom'
import { toast, ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'

function Restaurants() {

  const [visibleCount, setVisibleCount] = useState(6);
  const { cartItems, addTOCart } = useContext(CartContext);
  const restaurants = restaurantData.Restaurants;

  const getImage = (img) => {
    const name = img.split('/').pop().replace('restaurant', 'restaurent');
    return new URL(`../../assets/${name}`, import.meta.url).href;
  }

  const loadMore = () => {
    setVisibleCount(prev => prev + 6);
  };

  const handleBookTable = (restaurant) => {
    const item = {
      id: restaurant.id,
      title: restaurant.title,
      type: 'restaurant',
      slug: restaurant.slug,
      price: restaurant.price,
      location: restaurant.location,
      person: 1,
    }

    const alreadyExists = cartItems.find(h => h.id === restaurant.id);
    if (!alreadyExists) {
      addTOCart(item);
      toast.success(`${restaurant.title} added to cart`);
    } else {
      toast.info("Already in cart");
    }
  }

  return (
    <>
      <div className="main-wrapper">
        <ToastContainer />

        <div className="container">
          <div className="row">
            <div className="col-lg-3 mb-4">
              <div className="filter-sidebar shadow-sm">
                <h5 className="fw-bold mb-4 d-flex align-items-center">
                  <i className="ri-filter-3-fill me-2 text-secondary"></i>
                  Advanced Filter
                </h5>

                <fieldset className='filter-section'>
                  <legend><i className="ri-map-pin-line me-2"></i>Destination</legend>
                  <select className='form-select'>
                    <option value="">Select Destination</option>
                    <option>USA</option>
                    <option>Turkey</option>
                    <option>Switerland</option>
                    <option>ThaiLand</option>
                    <option>VietNam</option>
                  </select>
                </fieldset>

                <fieldset className='filter-section'>
                  <legend><i className="ri-restaurant-line me-2"></i>Restaurant Type</legend>
                  <select className='form-select'>
                    <option value="">Select Type</option>
                    <option>Fast Food</option>
                    <option>Traditional</option>
                    <option>Street Food</option>
                  </select>
                </fieldset>

                <fieldset className='filter-section'>
                  <legend><i className="ri-calendar-event-line me-2"></i>Date From</legend>
                  <input type="date" className='form-control' />
                </fieldset>

                <fieldset className='filter-section'>
                  <legend><i className="ri-user-smile-line me-2"></i>Guests</legend>
                  <input type="number" className='form-control' placeholder='number of Guest' min={1} />
                </fieldset>

                <fieldset className='filter-section'>
                  <legend><i className="ri-star-smile-line me-2"></i>Traveler Rating</legend>
                  <div className="d-flex flex-wrap gap-2">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <span key={star} className='rating-badge'>
                        <i className="ri-star-fill text-warning me-1"></i>
                        {star}
                      </span>
                    ))}
                  </div>
                </fieldset>

                <fieldset className='filter-section'>
                  <legend><i className="ri-price-tag-3-line me-2"></i>Special Offers</legend>
                  <div className="form-check">
                    <input type="checkbox" id='likely' className="form-check-input" />
                    <label htmlFor="likely" className='form-check-label'>Likely to Sell Out</label>
                  </div>
                  <div className="form-check">
                    <input type="checkbox" id='discount' className="form-check-input" />
                    <label htmlFor="discount" className='form-check-label'>Winter Discount</label>
                  </div>
                </fieldset>

                <fieldset className='filter-section'>
                  <legend><i className="ri-translate-2 me-2"></i>Languages</legend>
                  {["English", "Spanish", "French", "Bangla"].map((lang, i) => (
                    <div className="form-check" key={i}>
                      <input type="checkbox" className="form-check-input" id={lang} />
                      <label htmlFor={lang} className='form-check-label'>{lang}</label>
                    </div>
                  ))}
                </fieldset>
              </div>
            </div>

            <div className="col-lg-9">
              <div className="row">
                {restaurants && restaurants.slice(0, visibleCount).map((restaurant) => (
                  <div className="col-md-6 col-lg-4 mb-4 d-flex" key={restaurant.id}>
                    {/* Thêm d-flex vào col để các card bằng chiều cao */}
                    <div className="transport-card p-3 shadow-sm d-flex flex-column w-100">
                      <div className="position-relative mb-3">
                        <img
                          src={getImage(restaurant.image)}
                          className="img-fluid w-100 rounded-3"
                          alt={restaurant.title}
                          style={{ height: '200px', objectFit: 'cover' }}
                        />
                        <span className='badge position-absolute top-0 end-0 m-2 bg-primary text-white'>
                          <i className="ri-star-fill me-1"></i>
                          {restaurant.rating} ({restaurant.reviews})
                        </span>
                      </div>

                      <div className="card-body py-3 d-flex flex-column flex-grow-1">
                        <h6 className='fw-bold mb-1'>{restaurant.title}</h6>
                        <div className="text-muted mb-2">
                          <i className="ri-map-pin-line me-1"></i>
                          {restaurant.location}
                        </div>

                        <div className="d-flex align-items-center gap-2 mb-3">
                          <span className='badge bg-dark'>{restaurant.tag}</span>
                          {restaurant.oldprice && (
                            <small className="text-muted text-decoration-line-through">
                              ${restaurant.oldprice}
                            </small>
                          )}
                          <small className="fw-semibold text-success">
                            ${restaurant.price}
                          </small>
                        </div>

                        <div className="d-flex justify-content-between align-items-center mt-auto pt-2">
                          <span className='fw-semibold text-primary fs-6'>
                            ${restaurant.price}
                            <small className="text-muted fw-normal"> /meal</small>
                          </span>
                          <button
                            className="btn btn-outline-primary btn-sm"
                            onClick={() => handleBookTable(restaurant)}
                          >
                            Book Table
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>

              {restaurants && visibleCount < restaurants.length && (
                <div className="text-center mt-4">
                  <button className="btn btn-primary" onClick={loadMore}>
                    Load More
                  </button>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </>
  )
}

export default Restaurants