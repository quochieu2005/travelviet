import React, { useContext, useState, useEffect } from 'react'
import { CartContext } from '../../context/CartContext'
import { toast, ToastContainer } from 'react-toastify'
import axios from 'axios'
import 'react-toastify/dist/ReactToastify.css'

function Transport() {
    const [transports, setTransports] = useState([]);
    const [destinations, setDestinations] = useState([]);
    
    const [filters, setFilters] = useState({
        destination_id: '',
        transmission: '',
        search: ''
    });

    const { cartItems, addTOCart } = useContext(CartContext);

    useEffect(() => {
        // Gọi đến hàm getTransportDestinations() trong Controller của bạn
        axios.get('http://127.0.0.1:8000/api/transport-destinations')
            .then(res => {
                if (res.data && res.data.success) {
                    setDestinations(res.data.data);
                }
            })
            .catch(err => console.error("Error loading destinations:", err));
    }, []);

    useEffect(() => {
        const fetchTransports = async () => {
            try {
                // Tạo query params dựa trên các bộ lọc hiện tại
                const params = {};
                if (filters.destination_id) params.destination_id = filters.destination_id;
                if (filters.transmission) params.transmission = filters.transmission;
                if (filters.search) params.search = filters.search;
                
                const response = await axios.get('http://127.0.0.1:8000/api/transports', { params });
                
                if (response.data && response.data.success) {
                    setTransports(response.data.data.data || []);
                }
            } catch (error) {
                console.error("Error fetching filtered transports:", error);
                toast.error("Không thể tải danh sách xe theo bộ lọc");
            }
        };

        fetchTransports();
    }, [filters]);

    const handleFilterChange = (e) => {
        const { name, value } = e.target;
        setFilters(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const getImage = (img) => {
        if (!img) return '';
        const name = img.split('/').pop();
        return new URL(`../../assets/${name}`, import.meta.url).href;
    }

    const handleBookNow = (transport) => {
        const item = {
            id: transport.id,
            title: transport.name,
            type: 'transport',
            slug: transport.slug,
            price: transport.price,
            location: transport.destination?.name || 'N/A',
            person: 1,
            image: transport.image,  
            quantity: 1,             
            days: 1, 
        }

        const alreadyExists = cartItems.find(h => h.id === transport.id);
        if (!alreadyExists) {
            addTOCart(item);
            toast.success(`${transport.name} added to cart`);
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
                        {/* SIDEBAR FILTER */}
                        <div className="col-lg-3 mb-4">
                            <div className="filter-sidebar shadow-sm">
                                <h5 className="fw-bold mb-4 d-flex align-items-center">
                                    <i className="ri-filter-3-fill me-2 text-secondary"></i>
                                    Advanced Filter
                                </h5>

                                {/* Tìm kiếm theo tên xe */}
                                <fieldset className='filter-section mb-3'>
                                    <legend><i className="ri-search-line me-2"></i>Search Vehicle</legend>
                                    <input 
                                        type="text" 
                                        className="form-control" 
                                        name="search"
                                        placeholder="Type car name..."
                                        value={filters.search}
                                        onChange={handleFilterChange}
                                    />
                                </fieldset>

                                {/* Lọc theo Destination động lấy từ DB */}
                                <fieldset className='filter-section mb-3'>
                                    <legend><i className="ri-map-pin-line me-2"></i>Destination</legend>
                                    <select 
                                        className='form-select'
                                        name="destination_id"
                                        value={filters.destination_id}
                                        onChange={handleFilterChange}
                                    >
                                        <option value="">Select Destination</option>
                                        {destinations.map(dest => (
                                            <option key={dest.id} value={dest.id}>
                                                {dest.name} ({dest.transport_count})
                                            </option>
                                        ))}
                                    </select>
                                </fieldset>

                                {/* Lọc theo loại hộp số (Transmission) */}
                                <fieldset className='filter-section mb-3'>
                                    <legend><i className="ri-settings-3-line me-2"></i>Transmission</legend>
                                    <select 
                                        className='form-select'
                                        name="transmission"
                                        value={filters.transmission}
                                        onChange={handleFilterChange}
                                    >
                                        <option value="">Select Transmission</option>
                                        <option value="Automatic">Automatic</option>
                                        <option value="Manual">Manual</option>
                                    </select>
                                </fieldset>

                                {/* Các filter tĩnh giữ nguyên giao diện */}
                                <fieldset className='filter-section mb-3'>
                                    <legend><i className="ri-calendar-event-line me-2"></i>Date From</legend>
                                    <input type="date" className='form-control' />
                                </fieldset>
                                <fieldset className='filter-section mb-3'>
                                    <legend><i className="ri-user-smile-line me-2"></i>Guests</legend>
                                    <input type="number" className='form-control' placeholder='number of Guest' min={1} />
                                </fieldset>
                            </div>
                        </div>

                        {/* LIST TRANSPORTS */}
                        <div className="col-lg-9">
                            <div className="row">
                                {transports.length > 0 ? (
                                    transports.map((transport) => (
                                        <div className="col-md-6 col-lg-4 mb-4" key={transport.id}>
                                            <div className="transport-card p-3 shadow-sm h-10 d-flex flex-column">
                                                <div className="position-relative mb-3">
                                                    <img
                                                        src={transport.image}
                                                        className="img-fluid w-100 rounded-3"
                                                        alt={transport.name}
                                                    />
                                                    <span className='badge position-absolute top-0 end-0 m-2 bg-primary text-white'>
                                                        <i className="ri-star-fill me-1"></i>
                                                        {transport.rating} ({transport.review})
                                                    </span>
                                                </div>

                                                <div className="card-body py-3">
                                                    <h6 className='fw-bold mb-1'>{transport.name}</h6>
                                                    <div className="text-muted mb-2">
                                                        <i className="ri-map-pin-line me-1"></i>
                                                        {transport.destination?.name || 'No Location'}
                                                    </div>

                                                    <div className="d-flex flex-wrap gap-2 text-muted mb-3 small">
                                                        <span className='d-flex align-items-center'>
                                                            <i className="ri-roadster-line me-1 text-primary"></i>
                                                            {transport.mileage}
                                                        </span>
                                                        <span className='d-flex align-items-center'>
                                                            <i className="ri-settings-3-line me-1 text-primary"></i>
                                                            {transport.transmission}
                                                        </span>
                                                        <span className='d-flex align-items-center'>
                                                            <i className="ri-steering-line me-1 text-primary"></i>
                                                            Seats: {transport.seats}
                                                        </span>
                                                        <span>
                                                            <i className="ri-repeat-line me-1 text-primary"></i>
                                                            Trips: {transport.trips}
                                                        </span>
                                                    </div>

                                                    <div className="d-flex justify-content-between align-items-center mt-auto">
                                                        <span className='fw-semibold text-primary'>
                                                            {(transport.price).toLocaleString('vi-VN')}₫ <small>/day</small>
                                                        </span>
                                                        <button
                                                            className="btn btn-outline-primary btn-sm text-white"
                                                            onClick={() => handleBookNow(transport)}
                                                        >
                                                            Book Now
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="col-12 text-center py-5 text-muted">
                                        Không tìm thấy phương tiện nào phù hợp với bộ lọc.
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Transport