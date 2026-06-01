import React, { useState } from 'react'

function Profile() {
  const [activeMenu, setActiveMenu] = useState('account')
  const [profileData, setProfileData] = useState({
    fullName: 'D23VHCN01N THAI TRUNG QUOC HIEU',
    shortName: 'THAI TRUNG QUOC HIEU',
    gender: 'Nam',
    dob: '15/05/1995',
    city: 'TP. Hồ Chí Minh',
    email: 'hieu.ttq@example.com',
    phone: '+84 123 456 789'
  })
  
  const [emails, setEmails] = useState([
    'hieu.ttq@example.com',
    'hieu.work@example.com'
  ])
  const [newEmail, setNewEmail] = useState('')
  const [showAddEmail, setShowAddEmail] = useState(false)

  const [bookings] = useState([
    { id: 1, tourName: 'Khám phá vịnh Hạ Long', date: '15/03/2024', price: '4,500,000đ', status: 'completed' },
    { id: 2, tourName: 'Sapa - Thị trấn trong sương', date: '25/10/2024', price: '3,200,000đ', status: 'upcoming' },
    { id: 3, tourName: 'Phú Quốc nghỉ dưỡng', date: '05/07/2024', price: '5,900,000đ', status: 'cancelled' }
  ])

  const [transactions] = useState([
    { id: 1, tourName: 'Khám phá vịnh Hạ Long', amount: '4,500,000đ', date: '15/03/2024', status: 'success' },
    { id: 2, tourName: 'Nha Trang biển gọi', amount: '2,800,000đ', date: '20/02/2024', status: 'success' },
    { id: 3, tourName: 'Đà Lạt mộng mơ', amount: '3,500,000đ', date: '10/01/2024', status: 'pending' }
  ])

  const [refunds] = useState([
    { id: 1, tourName: 'Phú Quốc nghỉ dưỡng', amount: '5,900,000đ', date: '06/07/2024', status: 'processing' }
  ])

  const [savedPassengers] = useState([
    { id: 1, name: 'Nguyễn Văn A', type: 'Người lớn', idNumber: '079204001234' },
    { id: 2, name: 'Trần Thị B', type: 'Người lớn', idNumber: '079204005678' },
    { id: 3, name: 'Nguyễn Bé C', type: 'Trẻ em', idNumber: '079204009876' }
  ])

  const [notifications, setNotifications] = useState({
    emailPromo: true,
    emailBooking: true,
    priceAlert: true,
    smsAlert: false
  })

  const addEmail = () => {
    if (newEmail && emails.length < 3) {
      setEmails([...emails, newEmail])
      setNewEmail('')
      setShowAddEmail(false)
    }
  }

  const removeEmail = (emailToRemove) => {
    setEmails(emails.filter(email => email !== emailToRemove))
  }

  const handleLogout = () => {
    if (window.confirm('Bạn có chắc chắn muốn đăng xuất?')) {
      // Xử lý đăng xuất ở đây
      console.log('Đã đăng xuất')
      // Ví dụ: clear token, redirect về trang login
      // localStorage.removeItem('token')
      // window.location.href = '/login'
    }
  }

  const getStatusBadge = (status) => {
    const badges = {
      completed: <span className="badge-success">Đã hoàn thành</span>,
      upcoming: <span className="badge-warning">Sắp diễn ra</span>,
      cancelled: <span className="badge-danger">Đã hủy</span>,
      success: <span className="badge-success">Thành công</span>,
      pending: <span className="badge-warning">Đang xử lý</span>,
      processing: <span className="badge-info">Đang xử lý</span>
    }
    return badges[status] || null
  }

  const menuItems = [
    { id: 'account', label: 'Tài Khoản', icon: 'ri-user-line' },
    { id: 'payment', label: 'Thanh toán', icon: 'ri-bank-card-line' },
    { id: 'bookings', label: 'Đặt chỗ của tôi', icon: 'ri-calendar-check-line' },
    { id: 'transactions', label: 'Danh sách giao dịch', icon: 'ri-history-line' },
    { id: 'refunds', label: 'Refunds', icon: 'ri-refund-line' },
    { id: 'priceAlert', label: 'Thông báo giá vé máy bay', icon: 'ri-flight-takeoff-line' },
    { id: 'savedPassengers', label: 'Thông tin hành khách đã lưu', icon: 'ri-group-line' },
    { id: 'notificationSettings', label: 'Cài đặt thông báo', icon: 'ri-notification-line' },
    { id: 'logout', label: 'Đăng Xuất', icon: 'ri-logout-box-line' }
  ]

  return (
    <div className="profile-page">
      <div className="profile-wrapper">
        {/* Sidebar */}
        <div className="profile-sidebar">
          <div className="user-card">
            <div className="user-avatar">
              <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=120" alt="Avatar" />
              <div className="user-level">
                <span>Bronze Priority</span>
                <div className="level-progress">
                  <div className="level-progress-bar" style={{ width: '0%' }}></div>
                </div>
                <div className="level-points">0 điểm</div>
              </div>
            </div>
          </div>

          <nav className="sidebar-nav">
            {menuItems.map(item => (
              <button
                key={item.id}
                className={`nav-item ${activeMenu === item.id ? 'active' : ''} ${item.id === 'logout' ? 'logout-item' : ''}`}
                onClick={() => {
                  if (item.id === 'logout') {
                    handleLogout()
                  } else {
                    setActiveMenu(item.id)
                  }
                }}
              >
                <i className={item.icon}></i>
                <span>{item.label}</span>
              </button>
            ))}
          </nav>
        </div>

        {/* Main Content */}
        <div className="profile-main">
          {/* Account Tab - Thông tin tài khoản tổng hợp */}
          {activeMenu === 'account' && (
            <>
              {/* Account Info Section */}
              <div className="content-section">
                <div className="section-header">
                  <h2>Thông tin tài khoản</h2>
                  <button className="edit-link">Chỉnh sửa</button>
                </div>
                <div className="section-links">
                  <a href="#">Mật khẩu & Bảo mật</a>
                </div>
              </div>

              {/* Personal Data */}
              <div className="content-section">
                <div className="section-header">
                  <h2>Dữ liệu cá nhân</h2>
                  <button className="edit-link">Chỉnh sửa</button>
                </div>
                <div className="info-grid">
                  <div className="info-row">
                    <span className="info-label">Tên đầy đủ</span>
                    <span className="info-value">{profileData.fullName}</span>
                  </div>
                  <div className="info-row">
                    <span className="info-label">Tên trong hồ sơ</span>
                    <span className="info-value">{profileData.shortName}</span>
                    <span className="info-note">Tên trong hồ sơ được rút ngắn từ họ tên của bạn.</span>
                  </div>
                  <div className="info-row">
                    <span className="info-label">Giới tính</span>
                    <span className="info-value">{profileData.gender}</span>
                  </div>
                  <div className="info-row">
                    <span className="info-label">Ngày sinh</span>
                    <span className="info-value">{profileData.dob}</span>
                  </div>
                  <div className="info-row">
                    <span className="info-label">Thành phố cư trú</span>
                    <span className="info-value">{profileData.city}</span>
                  </div>
                </div>
                <div className="form-actions">
                  <button className="btn-secondary">Có lẽ để sau</button>
                  <button className="btn-primary">Lưu</button>
                </div>
              </div>

              {/* Email Section */}
              <div className="content-section">
                <div className="section-header">
                  <h2>Email</h2>
                  <span className="section-note">Chỉ có thể sử dụng tối đa 3 email</span>
                </div>
                <div className="email-list">
                  {emails.map((email, index) => (
                    <div key={index} className="email-item">
                      <span>{email}</span>
                      <button className="remove-email" onClick={() => removeEmail(email)}>Xóa</button>
                    </div>
                  ))}
                </div>
                {showAddEmail ? (
                  <div className="add-email-form">
                    <input 
                      type="email" 
                      placeholder="Nhập email mới"
                      value={newEmail}
                      onChange={(e) => setNewEmail(e.target.value)}
                      className="email-input"
                    />
                    <div className="add-email-actions">
                      <button className="btn-secondary" onClick={() => setShowAddEmail(false)}>Hủy</button>
                      <button className="btn-primary" onClick={addEmail}>Thêm</button>
                    </div>
                  </div>
                ) : (
                  emails.length < 3 && (
                    <button className="add-email-btn" onClick={() => setShowAddEmail(true)}>
                      <i className="ri-add-line"></i> Thêm email
                    </button>
                  )
                )}
              </div>
            </>
          )}

          {/* Payment Section */}
          {activeMenu === 'payment' && (
            <div className="content-section payment-section">
              <h2>Phương thức thanh toán</h2>
              <div className="payment-methods">
                <div className="payment-card">
                  <i className="ri-visa-line"></i>
                  <span>Visa **** 1234</span>
                  <span className="default-badge">Mặc định</span>
                </div>
                <div className="payment-card">
                  <i className="ri-mastercard-line"></i>
                  <span>Mastercard **** 5678</span>
                </div>
                <button className="add-payment-btn">+ Thêm phương thức thanh toán</button>
              </div>
            </div>
          )}

          {/* Bookings Section */}
          {activeMenu === 'bookings' && (
            <div className="content-section bookings-section">
              <h2>Đặt chỗ của tôi</h2>
              <div className="bookings-list">
                {bookings.map(booking => (
                  <div key={booking.id} className="booking-item">
                    <div className="booking-info">
                      <h4>{booking.tourName}</h4>
                      <p>{booking.date}</p>
                      <p className="booking-price">{booking.price}</p>
                    </div>
                    <div className="booking-status">
                      {getStatusBadge(booking.status)}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}

          {/* Transactions Section */}
          {activeMenu === 'transactions' && (
            <div className="content-section transactions-section">
              <h2>Danh sách giao dịch</h2>
              <table className="transactions-table">
                <thead>
                  <tr>
                    <th>Mã giao dịch</th>
                    <th>Tour</th>
                    <th>Số tiền</th>
                    <th>Ngày</th>
                    <th>Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  {transactions.map(tx => (
                    <tr key={tx.id}>
                      <td>#{tx.id}</td>
                      <td>{tx.tourName}</td>
                      <td>{tx.amount}</td>
                      <td>{tx.date}</td>
                      <td>{getStatusBadge(tx.status)}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}

          {/* Refunds Section */}
          {activeMenu === 'refunds' && (
            <div className="content-section refunds-section">
              <h2>Yêu cầu hoàn tiền</h2>
              {refunds.length > 0 ? (
                <div className="refunds-list">
                  {refunds.map(refund => (
                    <div key={refund.id} className="refund-item">
                      <div>
                        <h4>{refund.tourName}</h4>
                        <p>{refund.date}</p>
                      </div>
                      <div>
                        <p className="refund-amount">{refund.amount}</p>
                        {getStatusBadge(refund.status)}
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="empty-state">Chưa có yêu cầu hoàn tiền nào</p>
              )}
            </div>
          )}

          {/* Price Alert Section */}
          {activeMenu === 'priceAlert' && (
            <div className="content-section alert-section">
              <h2>Thông báo giá vé máy bay</h2>
              <div className="alert-form">
                <div className="alert-row">
                  <input type="text" placeholder="Điểm đi" className="alert-input" />
                  <i className="ri-arrow-right-line"></i>
                  <input type="text" placeholder="Điểm đến" className="alert-input" />
                </div>
                <div className="alert-row">
                  <input type="date" className="alert-input" />
                  <input type="date" className="alert-input" />
                </div>
                <button className="btn-primary">+ Thêm thông báo giá</button>
              </div>
            </div>
          )}

          {/* Saved Passengers Section */}
          {activeMenu === 'savedPassengers' && (
            <div className="content-section passengers-section">
              <h2>Thông tin hành khách đã lưu</h2>
              <div className="passengers-list">
                {savedPassengers.map(passenger => (
                  <div key={passenger.id} className="passenger-item">
                    <i className="ri-user-line"></i>
                    <div>
                      <h4>{passenger.name}</h4>
                      <p>{passenger.type} • {passenger.idNumber}</p>
                    </div>
                    <button className="edit-passenger">Chỉnh sửa</button>
                  </div>
                ))}
              </div>
              <button className="add-passenger-btn">+ Thêm hành khách</button>
            </div>
          )}

          {/* Notification Settings Section */}
          {activeMenu === 'notificationSettings' && (
            <div className="content-section settings-section">
              <h2>Cài đặt thông báo</h2>
              <div className="settings-list">
                <label className="setting-item">
                  <input 
                    type="checkbox" 
                    checked={notifications.emailPromo}
                    onChange={(e) => setNotifications({...notifications, emailPromo: e.target.checked})}
                  />
                  <span>Nhận ưu đãi và khuyến mãi qua email</span>
                </label>
                <label className="setting-item">
                  <input 
                    type="checkbox" 
                    checked={notifications.emailBooking}
                    onChange={(e) => setNotifications({...notifications, emailBooking: e.target.checked})}
                  />
                  <span>Nhận xác nhận đặt chỗ qua email</span>
                </label>
                <label className="setting-item">
                  <input 
                    type="checkbox" 
                    checked={notifications.priceAlert}
                    onChange={(e) => setNotifications({...notifications, priceAlert: e.target.checked})}
                  />
                  <span>Thông báo khi giá thay đổi</span>
                </label>
                <label className="setting-item">
                  <input 
                    type="checkbox" 
                    checked={notifications.smsAlert}
                    onChange={(e) => setNotifications({...notifications, smsAlert: e.target.checked})}
                  />
                  <span>Nhận thông báo qua SMS</span>
                </label>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  )
}

export default Profile