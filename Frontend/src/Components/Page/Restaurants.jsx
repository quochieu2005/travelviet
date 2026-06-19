import React, { useContext, useState, useEffect } from 'react'
import { CartContext } from '../../context/CartContext'
import restaurantApi from "/src/services/restaurantApi.js";
import { Link } from 'react-router-dom'
import { toast, ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'

function Restaurants() {
  const [restaurants, setRestaurants] = useState([]);
  const [loading, setLoading] = useState(true);
  const [visibleCount, setVisibleCount] = useState(6);
  const { cartItems, addTOCart } = useContext(CartContext);

  // Gọi API lấy dữ liệu thực tế khi component mount
  useEffect(() => {
    const fetchRestaurants = async () => {
      try {
        setLoading(true);
        const response = await restaurantApi.getRestaurants();
        
        // Tùy theo cấu trúc JSON Backend của bạn trả về
        if (response && response.data) {
          setRestaurants(response.data);
        } else if (Array.isArray(response)) {
          setRestaurants(response);
        } else {
          setRestaurants([]);
        }
      } catch (error) {
        toast.error("Failed to load restaurants data from server");
        console.error(error);
        setRestaurants([]);
      } finally {
        setLoading(false);
      }
    };

    fetchRestaurants();
  }, []);

  // Hàm lấy tên destination
  const getDestinationName = (restaurant) => {
    // Kiểm tra nếu có destination relation
    if (restaurant.destination && restaurant.destination.name) {
      return restaurant.destination.name;
    }
    // Fallback cho trường hợp dữ liệu cũ
    if (restaurant.location) {
      return restaurant.location;
    }
    return 'Đang cập nhật';
  };

  // Hàm xử lý hiển thị ảnh
  const getRestaurantImage = (imagePath) => {
    if (!imagePath) return 'https://placehold.co/600x400?text=No+Image';
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
      return imagePath;
    }
    return new URL(`../../assets/${imagePath}`, import.meta.url).href;
  };

  const loadMore = () => {
    setVisibleCount(prev => prev + 6);
  };

  // Hàm handleBookTable
  const handleBookTable = (restaurant) => {
    // Kiểm tra đã tồn tại chưa (cả id và type)
    const alreadyExists = cartItems.find(
      item => item.id === restaurant.id && item.type === 'restaurant'
    );
    
    if (alreadyExists) {
      toast.info(`${restaurant.title} đã có trong giỏ hàng!`);
      return;
    }

    // Tạo item với đầy đủ thông tin
    const item = {
      id: restaurant.id,
      title: restaurant.title,
      type: 'restaurant',
      slug: restaurant.slug,
      price: parseFloat(restaurant.price) || 0,
      location: getDestinationName(restaurant), // Sửa thành getDestinationName
      image: getRestaurantImage(restaurant.image),
      quantity: 1,
      adults: 2,
      children: 0,
    }

    addTOCart(item);
    toast.success(`Đã thêm ${restaurant.title} vào giỏ hàng!`);
  }

  // Format tiền VND
  const formatVND = (price) => {
    return Number(price || 0).toLocaleString('vi-VN') + '₫';
  }

  return (
    <>
      <div className="main-wrapper">
        <ToastContainer />

        <div className="container">
          <div className="row">
            {/* Sidebar Filter */}
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

            {/* List Restaurants */}
            <div className="col-lg-9">
              {loading ? (
                <div className="text-center py-5">
                  <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading restaurants...</span>
                  </div>
                </div>
              ) : (
                <div className="row">
                  {restaurants && restaurants.length > 0 ? (
                    restaurants.slice(0, visibleCount).map((restaurant) => (
                      <div className="col-md-6 col-lg-4 mb-4 d-flex" key={restaurant.id}>
                        <div className="restaurant-card p-3 shadow-sm d-flex flex-column w-100">
                          <div className="position-relative mb-3">
                            <img
                              src={getRestaurantImage(restaurant.image)}
                              className="img-fluid w-100 rounded-3"
                              alt={restaurant.title}
                              style={{ height: '200px', objectFit: 'cover' }}
                              onError={(e) => {
                                e.target.onerror = null;
                                e.target.src = 'https://placehold.co/600x400?text=No+Image';
                              }}
                            />
                            <span className='badge position-absolute top-0 end-0 m-2 bg-primary text-white'>
                              <i className="ri-star-fill me-1"></i>
                              {restaurant.rating ?? 0} ({restaurant.reviews ?? 0})
                            </span>
                          </div>

                          <div className="card-body py-3 d-flex flex-column flex-grow-1">
                            <h6 className='fw-bold mb-1'>{restaurant.title}</h6>
                            <div className="text-muted mb-2 text-truncate">
                              <i className="ri-map-pin-line me-1"></i>
                              {/* SỬA: Hiển thị tên destination thay vì location */}
                              {getDestinationName(restaurant)}
                            </div>

                            <div className="d-flex align-items-center gap-2 mb-3">
                              {restaurant.tag && <span className='badge bg-dark'>{restaurant.tag}</span>}
                              {restaurant.oldprice && (
                                <small className="text-muted text-decoration-line-through">
                                  {formatVND(restaurant.oldprice)}
                                </small>
                              )}
                            </div>

                            <div className="d-flex justify-content-between align-items-center mt-auto pt-2">
                              <span className='fw-semibold text-primary fs-6'>
                                {formatVND(restaurant.price)}
                                <small className="text-muted fw-normal"> /suất</small>
                              </span>
                              <button
                                className="btn btn-outline-primary btn-sm text-white"
                                onClick={() => handleBookTable(restaurant)}
                              >
                                Đặt bàn
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    ))
                  ) : (
                    <div className="col-12 text-center py-5">
                      <p className="text-muted">Không tìm thấy nhà hàng nào.</p>
                    </div>
                  )}
                </div>
              )}

              {/* Load More Button */}
              {!loading && restaurants && visibleCount < restaurants.length && (
                <div className="text-center mt-4">
                  <button className="btn btn-primary" onClick={loadMore}>
                    Xem thêm
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