import React, { useContext, useState } from 'react'
import { CartContext } from '../../context/CartContext'
import hotelData from '../../data/Hotel.json'
import { Link } from 'react-router-dom'
import { toast, ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'
import 'bootstrap-icons/font/bootstrap-icons.css'  // ✅ Thêm import Bootstrap Icons

function Hotels() {

    const [visibleCount, setVisibleCount] = useState(6);

    const { cartItems, addTOCart } = useContext(CartContext);

    const hotels = hotelData.hotels;

    const getImage = (img) => {
        const name = img.split('/').pop();
        return new URL(`../../assets/${name}`, import.meta.url).href;
    }

    const toVND = (usd) => (usd * 25000).toLocaleString('vi-VN') + '₫';

    const loadMore = () => {
        setVisibleCount(prev => prev + 6);
    };

    const handleBookNow = (hotel) => {
        const item = {
            id: hotel.id,
            title: hotel.name,
            type: 'hotel',
            slug: hotel.slug,
            price: hotel.price,
            location: hotel.location,
            person: 1,
        }

        const alreadyExists = cartItems.find(h => h.id === hotel.id);
        if (!alreadyExists) {
            addTOCart(item);
            toast.success(`${hotel.name} added to cart`);
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
                                    <legend><i className="ri-flight-takeoff-line me-2"></i>Hotel Type</legend>
                                    <select className='form-select'>
                                        <option value="">Select Type</option>
                                        <option>Adventure</option>
                                        <option>Relaxation</option>
                                        <option>Cultural</option>
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
                                {hotels.slice(0, visibleCount).map((hotel) => (
                                    <div className="col-md-6 col-lg-4 mb-4" key={hotel.id}>
                                        <div className="hotel-card p-3 shadow-sm h-10">
                                            <div className="position-relative mb-3">
                                                <img
                                                    src={getImage(hotel.image)}
                                                    className="img-fluid w-100 rounded-3"
                                                    alt={hotel.name}
                                                />
                                                <span className='badge position-absolute top-0 end-0 m-2'>
                                                    <i className="ri-star-fill me-1"></i>
                                                    {hotel.rating} ({hotel.reviews})
                                                </span>
                                            </div>

                                            <div className="card-body py-3">
                                                <h6 className='fw-bold mb-1'>{hotel.name}</h6>
                                                <div className="text-muted mb-2">
                                                    <i className="ri-map-pin-line me-1"></i>
                                                    {hotel.location}
                                                </div>

                                                {/* ✅ ĐÃ SỬA: Dùng trực tiếp icon từ JSON (Bootstrap Icons) */}
                                                <div className="d-flex flex-wrap gap-2 text-muted mb-3 small">
                                                    {hotel.facilities.map((item, idx) => (
                                                        <span key={idx} className='d-flex align-items-center'>
                                                            <i className={`${item.icon} me-1`}></i>
                                                            {item.name}
                                                        </span>
                                                    ))}
                                                </div>

                                                <div className="d-flex justify-content-between align-items-center mt-auto">
                                                    <span className='fw-semibold text-primary'>
                                                        {(hotel.price).toLocaleString('vi-VN')}₫ <small>/Person</small>
                                                    </span>

                                                    <button
                                                        className="btn btn-outline-primary btn-sm text-white"
                                                        onClick={() => handleBookNow(hotel)}
                                                    >
                                                        Book Now
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Load More Button */}
                            {visibleCount < hotels.length && (
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

export default Hotels