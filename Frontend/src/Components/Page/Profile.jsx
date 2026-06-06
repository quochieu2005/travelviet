import React, { useState, useEffect, useCallback, useMemo, useRef } from 'react'
import { getProfile, updateProfile, logout as logoutAPI } from '../../services/authService'
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import { useAuth } from '../../context/AuthContext';

function Profile() {
  const navigate = useNavigate();
  const { user: authUser, logout, updateUser } = useAuth();
  const fileInputRef = useRef(null);

  const [activeMenu, setActiveMenu] = useState('account')
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  // Edit modal state
  const [showEditModal, setShowEditModal] = useState(false)
  const [editLoading, setEditLoading] = useState(false)
  const [avatarPreview, setAvatarPreview] = useState(null)
  const [editForm, setEditForm] = useState({
    full_name: '',
    phone: '',
    address: '',
    nationality: '',
    avatar: null,
  })

  const [bookings, setBookings] = useState([])
  const [transactions, setTransactions] = useState([])
  const [refunds, setRefunds] = useState([])
  const [savedPassengers, setSavedPassengers] = useState([])
  const [emails, setEmails] = useState([])
  const [newEmail, setNewEmail] = useState('')
  const [showAddEmail, setShowAddEmail] = useState(false)
  const [notifications, setNotifications] = useState({
    emailPromo: false,
    emailBooking: false,
    priceAlert: false,
    smsAlert: false
  })

  const CACHE_KEY = 'user_profile_cache'
  const CACHE_DURATION = 5 * 60 * 1000

  const getStoredUser = () => {
    try {
      const storedUser = localStorage.getItem('user')
      if (storedUser) return JSON.parse(storedUser)
    } catch (e) { }
    return null
  }

  const fetchProfile = useCallback(async (skipCache = false) => {
    if (!skipCache) {
      const cached = localStorage.getItem(CACHE_KEY)
      if (cached) {
        try {
          const { data, timestamp } = JSON.parse(cached)
          if (Date.now() - timestamp < CACHE_DURATION) {
            setUser(data.user)
            if (data.user?.email) setEmails([data.user.email])
            setLoading(false)
            return
          }
        } catch (e) { }
      }
    }

    setLoading(true)
    setError('')

    try {
      const response = await getProfile()
      const userData = response.data.user
      setUser(userData)
      localStorage.setItem('user', JSON.stringify(userData))
      updateUser(userData)
      if (userData?.email) setEmails([userData.email])
      localStorage.setItem(CACHE_KEY, JSON.stringify({ data: response.data, timestamp: Date.now() }))
      setLoading(false)
    } catch (err) {
      // interceptor trong authService đã xử lý 401 rồi
      setError('Không thể tải thông tin tài khoản.')
      setLoading(false)
    }
  }, [updateUser])

  // Mở edit modal — điền sẵn data hiện tại
  const openEditModal = useCallback(() => {
    if (user) {
      setEditForm({
        full_name: user.full_name || '',
        phone: user.phone || '',
        address: user.address || '',
        nationality: user.nationality || '',
        avatar: null,
      })
      setAvatarPreview(
        user.avatar ? `http://localhost:8000/${user.avatar}` : null
      )
    }
    setShowEditModal(true)
  }, [user])

  const closeEditModal = useCallback(() => {
    setShowEditModal(false)
    setAvatarPreview(null)
    setEditForm({ full_name: '', phone: '', address: '', nationality: '', avatar: null })
    if (fileInputRef.current) fileInputRef.current.value = ''
  }, [])

  const handleEditChange = (e) => {
    const { name, value } = e.target
    setEditForm(prev => ({ ...prev, [name]: value }))
  }

  const handleAvatarChange = (e) => {
    const file = e.target.files[0]
    if (!file) return
    if (file.size > 2 * 1024 * 1024) {
      toast.error('Ảnh phải nhỏ hơn 2MB!')
      return
    }
    setEditForm(prev => ({ ...prev, avatar: file }))
    setAvatarPreview(URL.createObjectURL(file))
  }

  const handleEditSubmit = async (e) => {
    e.preventDefault()
    setEditLoading(true)

    try {
      const formData = new FormData()
      formData.append('full_name', editForm.full_name)
      formData.append('phone', editForm.phone)
      formData.append('address', editForm.address)
      formData.append('nationality', editForm.nationality)
      if (editForm.avatar) {
        formData.append('avatar', editForm.avatar)
      }

      const response = await updateProfile(formData)
      const updatedUser = response.data.user

      // Cập nhật khắp nơi
      setUser(updatedUser)
      localStorage.setItem('user', JSON.stringify(updatedUser))
      localStorage.removeItem(CACHE_KEY) // xóa cache cũ
      updateUser(updatedUser)

      toast.success('Cập nhật hồ sơ thành công!')
      closeEditModal()
    } catch (err) {
      const msg = err.response?.data?.message
        || err.response?.data?.errors
        || 'Cập nhật thất bại, vui lòng thử lại.'
      const errorMessage = typeof msg === 'object'
        ? Object.values(msg).flat().join(' ')
        : msg
      toast.error(errorMessage)
    } finally {
      setEditLoading(false)
    }
  }

  const handleLogout = useCallback(async () => {
    if (!window.confirm('Bạn có chắc chắn muốn đăng xuất?')) return
    try { await logoutAPI() } catch (_) { }
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    localStorage.removeItem(CACHE_KEY)
    logout()
    toast.success('Đăng xuất thành công!')
    navigate('/')
  }, [logout, navigate])

  useEffect(() => {
    const token = localStorage.getItem('token')
    if (!token) { navigate('/Login'); return }
    const storedUser = getStoredUser()
    if (storedUser) {
      setUser(storedUser)
      if (storedUser?.email) setEmails([storedUser.email])
      setLoading(false)
    }
    fetchProfile()
  }, []) // chỉ chạy 1 lần khi mount

  const addEmail = useCallback(() => {
    if (newEmail && emails.length < 3 && !emails.includes(newEmail)) {
      setEmails([...emails, newEmail])
      setNewEmail('')
      setShowAddEmail(false)
    }
  }, [newEmail, emails])

  const removeEmail = useCallback((emailToRemove) => {
    setEmails(emails.filter(e => e !== emailToRemove))
  }, [emails])

  const getStatusBadge = useCallback((status) => {
    const badges = {
      completed: <span className="badge-success">Đã hoàn thành</span>,
      upcoming: <span className="badge-warning">Sắp diễn ra</span>,
      cancelled: <span className="badge-danger">Đã hủy</span>,
      success: <span className="badge-success">Thành công</span>,
      pending: <span className="badge-warning">Đang xử lý</span>,
    }
    return badges[status] || null
  }, [])

  const getInitial = useCallback((name) => name ? name.charAt(0).toUpperCase() : '?', [])

  const menuItems = useMemo(() => [
    { id: 'account', label: 'Tài Khoản', icon: 'ri-user-line' },
    { id: 'payment', label: 'Thanh toán', icon: 'ri-bank-card-line' },
    { id: 'bookings', label: 'Đặt chỗ của tôi', icon: 'ri-calendar-check-line' },
    { id: 'transactions', label: 'Danh sách giao dịch', icon: 'ri-history-line' },
    { id: 'refunds', label: 'Refunds', icon: 'ri-refund-line' },
    { id: 'priceAlert', label: 'Thông báo giá vé máy bay', icon: 'ri-flight-takeoff-line' },
    { id: 'savedPassengers', label: 'Thông tin hành khách đã lưu', icon: 'ri-group-line' },
    { id: 'notificationSettings', label: 'Cài đặt thông báo', icon: 'ri-notification-line' },
    { id: 'logout', label: 'Đăng Xuất', icon: 'ri-logout-box-line' },
  ], [])

  return (
    <div className="profile-page">
      <div className="profile-wrapper">

        {/* Sidebar */}
        <div className="profile-sidebar">
          <div className="user-card">
            <div className="user-avatar">
              {user?.avatar ? (
                <img
                  src={user.avatar}
                  alt={user.full_name}
                  style={{
                    width: 80,
                    height: 80,
                    borderRadius: '50%',
                    objectFit: 'cover'
                  }}
                />
              ) : (
                <div className="avatar-initial">{getInitial(user?.full_name)}</div>
              )}
              
            </div>
            <div className="user-info text-center text-white">
              <h3>{user?.full_name || 'Khách'}</h3>
              <p>{user?.email || ''}</p>
            </div>
          </div>

          <nav className="sidebar-nav">
            {menuItems.map(item => (
              <button
                key={item.id}
                className={`nav-item ${activeMenu === item.id ? 'active' : ''} ${item.id === 'logout' ? 'logout-item' : ''}`}
                onClick={() => item.id === 'logout' ? handleLogout() : setActiveMenu(item.id)}
              >
                <i className={item.icon}></i>
                <span>{item.label}</span>
              </button>
            ))}
          </nav>
        </div>

        {/* Main Content */}
        <div className="profile-main">

          {activeMenu === 'account' && (
            <>
              <div className="content-section">
                <div className="section-header">
                  <h2>Thông tin tài khoản</h2>
                  <button className="edit-link" onClick={openEditModal}>
                    <i className="ri-edit-line"></i> Chỉnh sửa
                  </button>
                </div>
                <div className="section-links">
                  <a href="#">Mật khẩu &amp; Bảo mật</a>
                </div>
              </div>

              <div className="content-section">
                <div className="section-header">
                  <h2>Dữ liệu cá nhân</h2>
                  <button className="edit-link" onClick={openEditModal}>
                    <i className="ri-edit-line"></i> Chỉnh sửa
                  </button>
                </div>
                <div className="info-grid">
                  <div className="info-row">
                    <span className="info-label">Tên đầy đủ</span>
                    <span className="info-value">{user?.full_name || '—'}</span>
                  </div>
                  <div className="info-row">
                    <span className="info-label">Email</span>
                    <span className="info-value">{user?.email || '—'}</span>
                  </div>
                  <div className="info-row">
                    <span className="info-label">Số điện thoại</span>
                    <span className="info-value">{user?.phone || '—'}</span>
                  </div>
                  <div className="info-row">
                    <span className="info-label">Địa chỉ</span>
                    <span className="info-value">{user?.address || '—'}</span>
                  </div>
                  <div className="info-row">
                    <span className="info-label">Quốc tịch</span>
                    <span className="info-value">{user?.nationality || '—'}</span>
                  </div>
                </div>
              </div>

              <div className="content-section">
                <div className="section-header">
                  <h2>Email</h2>
                  <span className="section-note">Chỉ có thể sử dụng tối đa 3 email</span>
                </div>
                <div className="email-list">
                  {emails.map((email, index) => (
                    <div key={index} className="email-item">
                      <span>{email}</span>
                      {index !== 0 && (
                        <button className="remove-email" onClick={() => removeEmail(email)}>Xóa</button>
                      )}
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

          {activeMenu === 'payment' && (
            <div className="content-section payment-section">
              <h2>Phương thức thanh toán</h2>
              <p className="empty-state">Chưa có phương thức thanh toán nào.</p>
              <button className="add-payment-btn">+ Thêm phương thức thanh toán</button>
            </div>
          )}

          {activeMenu === 'bookings' && (
            <div className="content-section bookings-section">
              <h2>Đặt chỗ của tôi</h2>
              <p className="empty-state">Chưa có đặt chỗ nào.</p>
            </div>
          )}

          {activeMenu === 'transactions' && (
            <div className="content-section transactions-section">
              <h2>Danh sách giao dịch</h2>
              <p className="empty-state">Chưa có giao dịch nào.</p>
            </div>
          )}

          {activeMenu === 'refunds' && (
            <div className="content-section refunds-section">
              <h2>Yêu cầu hoàn tiền</h2>
              <p className="empty-state">Chưa có yêu cầu hoàn tiền nào.</p>
            </div>
          )}

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

          {activeMenu === 'savedPassengers' && (
            <div className="content-section passengers-section">
              <h2>Thông tin hành khách đã lưu</h2>
              <p className="empty-state">Chưa có hành khách nào được lưu.</p>
              <button className="add-passenger-btn">+ Thêm hành khách</button>
            </div>
          )}

          {activeMenu === 'notificationSettings' && (
            <div className="content-section settings-section">
              <h2>Cài đặt thông báo</h2>
              <div className="settings-list">
                {[
                  { key: 'emailPromo', label: 'Nhận ưu đãi và khuyến mãi qua email' },
                  { key: 'emailBooking', label: 'Nhận xác nhận đặt chỗ qua email' },
                  { key: 'priceAlert', label: 'Thông báo khi giá thay đổi' },
                  { key: 'smsAlert', label: 'Nhận thông báo qua SMS' },
                ].map(({ key, label }) => (
                  <label key={key} className="setting-item">
                    <input
                      type="checkbox"
                      checked={notifications[key]}
                      onChange={(e) => setNotifications({ ...notifications, [key]: e.target.checked })}
                    />
                    <span>{label}</span>
                  </label>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>

      {/* ===== EDIT PROFILE MODAL ===== */}
      {showEditModal && (
        <div
          className="modal-overlay"
          onClick={(e) => e.target === e.currentTarget && closeEditModal()}
          style={{
            position: 'fixed', inset: 0,
            backgroundColor: 'rgba(0,0,0,0.6)',
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            zIndex: 9999, padding: '20px'
          }}
        >
          <div style={{
            backgroundColor: '#1a1d2e',
            borderRadius: '16px',
            width: '100%', maxWidth: '560px',
            maxHeight: '90vh', overflowY: 'auto',
            padding: '32px',
            boxShadow: '0 25px 50px rgba(0,0,0,0.5)'
          }}>
            {/* Modal Header */}
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '28px' }}>
              <h3 style={{ margin: 0, color: '#fff', fontSize: '20px', fontWeight: 700 }}>
                Chỉnh sửa hồ sơ
              </h3>
              <button
                onClick={closeEditModal}
                style={{ background: 'none', border: 'none', color: '#aaa', fontSize: '24px', cursor: 'pointer', lineHeight: 1 }}
              >
                <i className="ri-close-line"></i>
              </button>
            </div>

            <form onSubmit={handleEditSubmit}>

              {/* Avatar Upload */}
              <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: '28px' }}>
                <div
                  onClick={() => fileInputRef.current?.click()}
                  style={{
                    width: 100, height: 100, borderRadius: '50%',
                    backgroundColor: '#f26f55',
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    cursor: 'pointer', overflow: 'hidden', position: 'relative',
                    border: '3px solid rgba(242,111,85,0.4)',
                    transition: 'opacity 0.2s'
                  }}
                  onMouseEnter={e => e.currentTarget.style.opacity = '0.8'}
                  onMouseLeave={e => e.currentTarget.style.opacity = '1'}
                >
                  {avatarPreview ? (
                    <img src={avatarPreview} alt="avatar" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                  ) : (
                    <span style={{ fontSize: 36, fontWeight: 700, color: '#fff' }}>
                      {getInitial(editForm.full_name || user?.full_name)}
                    </span>
                  )}
                  {/* Overlay icon */}
                  <div style={{
                    position: 'absolute', inset: 0, backgroundColor: 'rgba(0,0,0,0.4)',
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    opacity: 0, transition: 'opacity 0.2s'
                  }}
                    onMouseEnter={e => e.currentTarget.style.opacity = '1'}
                    onMouseLeave={e => e.currentTarget.style.opacity = '0'}
                  >
                    <i className="ri-camera-line" style={{ color: '#fff', fontSize: 24 }}></i>
                  </div>
                </div>
                <input
                  ref={fileInputRef}
                  type="file"
                  accept="image/jpg,image/jpeg,image/png,image/webp"
                  onChange={handleAvatarChange}
                  style={{ display: 'none' }}
                />
                <p style={{ margin: '8px 0 0', color: '#888', fontSize: '13px' }}>
                  Nhấp để thay đổi ảnh (tối đa 2MB)
                </p>
              </div>

              {/* Form Fields */}
              {[
                { name: 'full_name', label: 'Họ và tên', placeholder: 'vd: Nguyễn Văn A', type: 'text' },
                { name: 'phone', label: 'Số điện thoại', placeholder: 'vd: 0901234567', type: 'tel' },
                { name: 'address', label: 'Địa chỉ', placeholder: 'vd: 123 Nguyễn Huệ, TP.HCM', type: 'text' },
                { name: 'nationality', label: 'Quốc tịch', placeholder: 'vd: Việt Nam', type: 'text' },
              ].map(field => (
                <div key={field.name} style={{ marginBottom: '18px' }}>
                  <label style={{ display: 'block', color: '#ccc', fontSize: '13px', marginBottom: '6px', fontWeight: 500 }}>
                    {field.label}
                  </label>
                  <input
                    type={field.type}
                    name={field.name}
                    value={editForm[field.name]}
                    onChange={handleEditChange}
                    placeholder={field.placeholder}
                    disabled={editLoading}
                    style={{
                      width: '100%', padding: '10px 14px',
                      backgroundColor: '#252837', border: '1px solid #353849',
                      borderRadius: '8px', color: '#fff', fontSize: '14px',
                      outline: 'none', boxSizing: 'border-box',
                      transition: 'border-color 0.2s'
                    }}
                    onFocus={e => e.target.style.borderColor = '#f26f55'}
                    onBlur={e => e.target.style.borderColor = '#353849'}
                  />
                </div>
              ))}

              {/* Buttons */}
              <div style={{ display: 'flex', gap: '12px', marginTop: '28px' }}>
                <button
                  type="button"
                  onClick={closeEditModal}
                  disabled={editLoading}
                  style={{
                    flex: 1, padding: '12px',
                    backgroundColor: 'transparent', border: '1px solid #444',
                    borderRadius: '8px', color: '#ccc', fontSize: '14px',
                    cursor: 'pointer', fontWeight: 600
                  }}
                >
                  Hủy
                </button>
                <button
                  type="submit"
                  disabled={editLoading}
                  style={{
                    flex: 2, padding: '12px',
                    backgroundColor: '#f26f55', border: 'none',
                    borderRadius: '8px', color: '#fff', fontSize: '14px',
                    cursor: editLoading ? 'not-allowed' : 'pointer',
                    fontWeight: 700, opacity: editLoading ? 0.7 : 1,
                    transition: 'opacity 0.2s'
                  }}
                >
                  {editLoading ? (
                    <><i className="ri-loader-4-line"></i> Đang lưu...</>
                  ) : (
                    <><i className="ri-save-line"></i> Lưu thay đổi</>
                  )}
                </button>
              </div>

            </form>
          </div>
        </div>
      )}
    </div>
  )
}

export default Profile