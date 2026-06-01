import React from 'react'

function ContactSection() {
    return (
        <>
            <div className="contact-section main-wrapper">
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-xl-7 col-lg-9">
                            <div className="contact-card">
                                <h4 className="contact-heading">
                                    Feel free to Write Us Anytime
                                </h4>

                                <form action="post" className='contact-form'>
                                    <div className="row g-4">
                                        <div className="col-sm-6">
                                            <input type="text" className='form-control custom-input' name="" id="" placeholder='Enter Your Name' />
                                        </div>

                                        <div className="col-sm-6">
                                            <input type="email" className='form-control custom-input' name="" id="" placeholder='Enter Your Email' />
                                        </div>

                                        <div className="col-sm-6">
                                            <input type="text" className='form-control custom-input' name="" id="" placeholder='Your Phone' />
                                        </div>

                                        <div className="col-sm-6">
                                            <input type="text" className='form-control custom-input' name="" id="" placeholder='Select Subject' />
                                        </div>

                                        <div className="col-sm-12">
                                            <textarea type="text" className='form-control custom-textarea' rows="5" name="" id="" placeholder='Enter Your Message...'></textarea>
                                        </div>

                                    </div>

                                    <div className="mt-4">
                                        <button type='submit' className="btn send-btn">Send Message</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <div className="map-container">
                    <iframe
                        title='Google Map' 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15743722.794960847!2d95.23365315582103!3d15.555151669615714!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31157a4d736a1e5f%3A0xb03bb0c9e2fe62be!2zVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1780302719518!5m2!1svi!2s"  
                        className='map-frame'
                        allowFullScreen         
                        loading="lazy">
                    </iframe>
                </div>

            </div>
        </>
    )
}

export default ContactSection