import React, { useContext, useState, useEffect } from 'react'
import { CartContext } from '../../context/CartContext'
import { Link } from 'react-router-dom'
import { toast, ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import hotelService from '../../services/hotelService'

function Hotels() {
    const [visibleCount, setVisibleCount] = useState(6);
    const [hotels, setHotels] = useState([]);
    const [loading, setLoading] = useState(true);

    const { cartItems, addTOCart } = useContext(CartContext);

    // Fetch hotels from API using service
    useEffect(() => {
        const loadHotels = async () => {
            try {
                setLoading(true);
                const data = await hotelService.getHotels();
                setHotels(data);
            } catch (error) {
                console.error('Error:', error);
                setHotels([]);
            } finally {
                setLoading(false);
            }
        };
        loadHotels();
    }, []);

    const getImage = (img) => {
        if (!img) return '';
        if (img.startsWith('http://') || img.startsWith('https://')) {
            return img;
        }
        const name = img.split('/').pop();
        return new URL(`../../assets/${name}`, import.meta.url).href;
    }

    const formatVND = (price) => {
        return Number(price || 0).toLocaleString('vi-VN') + '₫';
    }

    const loadMore = () => {
        setVisibleCount(prev => prev + 6);
    };

    // ✅ SỬA LẠI HÀM handleBookNow
    const handleBookNow = (hotel) => {
        // Kiểm tra đã tồn tại chưa (cả id và type)
        const alreadyExists = cartItems.find(
            item => item.id === hotel.id && item.type === 'hotel'
        );
        
        if (alreadyExists) {
            toast.info(`${hotel.name} đã có trong giỏ hàng!`);
            return;
        }

        // Tạo item với đầy đủ thông tin
        const item = {
            id: hotel.id,
            title: hotel.name,
            type: 'hotel',                    // ✅ quan trọng
            slug: hotel.slug,
            price: parseFloat(hotel.price) || 0,
            location: hotel.location || 'N/A',
            image: hotel.thumbnail || getImage(hotel.image),  // ✅ thêm image
            quantity: 1,                      // số phòng
            nights: 1,                        // số đêm (có thể cho user chọn sau)
            // Thêm các tiện ích nếu cần hiển thị trong cart
            facilities: hotel.facilities || [],
            rating: hotel.rating || 0,
            reviews: hotel.reviews || 0
        }

        addTOCart(item);
        toast.success(`Đã thêm ${hotel.name} vào giỏ hàng!`);
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
                            {loading ? (
                                <div className="text-center py-5">
                                    <div className="spinner-border text-primary" role="status">
                                        <span className="visually-hidden">Loading hotels...</span>
                                    </div>
                                </div>
                            ) : (
                                <>
                                    <div className="row">
                                        {hotels.length > 0 ? (
                                            hotels.slice(0, visibleCount).map((hotel) => (
                                                <div className="col-md-6 col-lg-4 mb-4" key={hotel.id}>
                                                    <div className="hotel-card p-3 shadow-sm h-100 d-flex flex-column">
                                                        <div className="position-relative mb-3">
                                                            <img
                                                                src={hotel.thumbnail || getImage(hotel.image)}
                                                                className="img-fluid w-100 rounded-3"
                                                                alt={hotel.name}
                                                                style={{ height: '200px', objectFit: 'cover' }}
                                                                onError={(e) => {
                                                                    e.target.onerror = null;
                                                                    e.target.src = 'https://placehold.co/600x400?text=No+Image';
                                                                }}
                                                            />
                                                            <span className='badge position-absolute top-0 end-0 m-2 bg-primary text-white'>
                                                                <i className="ri-star-fill me-1"></i>
                                                                {hotel.rating || 0} ({hotel.reviews || 0})
                                                            </span>
                                                        </div>

                                                        <div className="card-body py-3 d-flex flex-column flex-grow-1">
                                                            <h6 className='fw-bold mb-1'>{hotel.name}</h6>
                                                            <div className="text-muted mb-2">
                                                                <i className="ri-map-pin-line me-1"></i>
                                                                {hotel.location}
                                                            </div>

                                                            <div className="d-flex flex-wrap gap-2 text-muted mb-3 small">
                                                                {hotel.facilities?.slice(0, 3).map((item, idx) => (
                                                                    <span key={idx} className='d-flex align-items-center'>
                                                                        <i className={`${item.icon || 'ri-check-line'} me-1`}></i>
                                                                        {item.name}
                                                                    </span>
                                                                ))}
                                                                {hotel.facilities?.length > 3 && (
                                                                    <span className='d-flex align-items-center'>
                                                                        +{hotel.facilities.length - 3} more
                                                                    </span>
                                                                )}
                                                            </div>

                                                            <div className="d-flex justify-content-between align-items-center mt-auto pt-2">
                                                                <span className='fw-semibold text-primary'>
                                                                    {formatVND(hotel.price)} <small>/đêm</small>
                                                                </span>

                                                                <button
                                                                    className="btn btn-outline-primary btn-sm text-white"
                                                                    onClick={() => handleBookNow(hotel)}
                                                                >
                                                                    Đặt phòng
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            ))
                                        ) : (
                                            <div className="col-12 text-center py-5">
                                                <p className="text-muted">Không tìm thấy khách sạn nào.</p>
                                            </div>
                                        )}
                                    </div>

                                    {/* Load More Button */}
                                    {!loading && visibleCount < hotels.length && (
                                        <div className="text-center mt-4">
                                            <button className="btn btn-primary" onClick={loadMore}>
                                                Xem thêm
                                            </button>
                                        </div>
                                    )}
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Hotels