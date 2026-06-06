import React, { useState } from 'react';
import { sendContact } from '../../services/contactService'; // điều chỉnh path cho phù hợp

function ContactSection() {
    const [formData, setFormData] = useState({
        full_name: '',
        email: '',
        phone: '',
        subject: '',
        message: ''
    });

    const [loading, setLoading] = useState(false);
    const [success, setSuccess] = useState('');
    const [error, setError] = useState('');

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setSuccess('');
        setError('');

        try {
            const response = await sendContact(formData);
            setSuccess(response.message || 'Gửi thành công!');
            setFormData({ full_name: '', email: '', phone: '', subject: '', message: '' });
        } catch (err) {
            setError(err.response?.data?.message || 'Có lỗi xảy ra, vui lòng thử lại!');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="contact-section main-wrapper">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-xl-7 col-lg-9">
                        <div className="contact-card">
                            <h4 className="contact-heading">Feel free to Write Us Anytime</h4>

                            {success && <div className="alert alert-success">{success}</div>}
                            {error && <div className="alert alert-danger">{error}</div>}

                            <form onSubmit={handleSubmit} className='contact-form'>
                                <div className="row g-4">
                                    <div className="col-sm-6">
                                        <input 
                                            type="text" 
                                            className='form-control custom-input' 
                                            name="full_name" 
                                            value={formData.full_name}
                                            onChange={handleChange}
                                            placeholder='Enter Your Name' 
                                            required 
                                        />
                                    </div>

                                    <div className="col-sm-6">
                                        <input 
                                            type="email" 
                                            className='form-control custom-input' 
                                            name="email" 
                                            value={formData.email}
                                            onChange={handleChange}
                                            placeholder='Enter Your Email' 
                                            required 
                                        />
                                    </div>

                                    <div className="col-sm-6">
                                        <input 
                                            type="text" 
                                            className='form-control custom-input' 
                                            name="phone" 
                                            value={formData.phone}
                                            onChange={handleChange}
                                            placeholder='Your Phone' 
                                            required 
                                        />
                                    </div>

                                    <div className="col-sm-6">
                                        <input 
                                            type="text" 
                                            className='form-control custom-input' 
                                            name="subject" 
                                            value={formData.subject}
                                            onChange={handleChange}
                                            placeholder='Select Subject' 
                                            required 
                                        />
                                    </div>

                                    <div className="col-sm-12">
                                        <textarea 
                                            className='form-control custom-textarea' 
                                            rows="5" 
                                            name="message" 
                                            value={formData.message}
                                            onChange={handleChange}
                                            placeholder='Enter Your Message...' 
                                            required
                                        ></textarea>
                                    </div>
                                </div>

                                <div className="mt-4">
                                    <button 
                                        type='submit' 
                                        className="btn send-btn" 
                                        disabled={loading}
                                    >
                                        {loading ? 'Đang gửi...' : 'Send Message'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ContactSection;