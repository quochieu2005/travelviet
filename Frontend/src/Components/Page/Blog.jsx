import React from 'react'

import vaniimg from '../../assets/news-6.png'

import news1 from "../../assets/news-1.png"
import news2 from "../../assets/news-2.png"
import news3 from "../../assets/news-3.png"
import news4 from "../../assets/news-4.png"
import news5 from "../../assets/news-5.png"
import news6 from "../../assets/news-6.png"
import news7 from "../../assets/news-7.png"
import news8 from "../../assets/news-8.png"
import news9 from "../../assets/news-9.png"

function BlogSection() {
    return (
        <>

            <div className="blog-section main-wrapper text-white">
                <div className="container">
                    <div className="row">
                        <div className="col-lg-6 mb-4">
                            <img src={vaniimg} alt="" className='blog-section_main-img img-fluid rounded' />
                        </div>

                        <div className="col-lg-6">
                            <div className="blog-section_post mb-4">
                                <small className="blog-section_small-text">Tour Guide</small>
                                <h6 className="blog-section_card-title">
                                    The World is a Book and Those Who do not Travel Read Only One Page.
                                </h6>

                                <div className="d-flex justify-content-between blog-section_meta small">
                                    <span>Crish Jorden</span>
                                    <span><i className="ri-time-line me-1">10 Min Read</i></span>
                                </div>
                            </div>

                            <div className="blog-section_post mb-4">
                                <small className="blog-section_small-text">Tour Guide</small>
                                <h6 className="blog-section_card-title">
                                    A Good Traveler Has no Fixed Plans and is Not Intent on Arriving
                                </h6>

                                <div className="d-flex justify-content-between blog-section_meta small">
                                    <span>David Warner</span>
                                    <span><i className="ri-time-line me-1">10 Min Read</i></span>
                                </div>
                            </div>

                            <div className="blog-section_post mb-4">
                                <small className="blog-section_small-text">Tour Guide</small>
                                <h6 className="blog-section_card-title">
                                    We Travel, Some of us Forever, to Seek Other States, Other Souls.
                                </h6>

                                <div className="d-flex justify-content-between blog-section_meta small">
                                    <span>David Malan</span>
                                    <span><i className="ri-time-line me-1">10 Min Read</i></span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div className="row pt-4">
                        {/* Item 1 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news1} alt="Travel Post 1" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">Travel</small>
                                    <h6 className="blog-section_card-title">
                                        Exploring the mountains: A Journey into the World
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>Sarah Lee</span>
                                        <span><i className="ri-time-line me-1">8 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Item 2 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news2} alt="Culture Post" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">Culture</small>
                                    <h6 className="blog-section_card-title">
                                        Immersing in Local Traditions: The Heart of the City
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>Michael Smith</span>
                                        <span><i className="ri-time-line me-1">6 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Item 3 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news3} alt="Food Post" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">Food</small>
                                    <h6 className="blog-section_card-title">
                                        A Taste of Italy: Pasta, Pizza, and Culinary Passion
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>Emma Johnson</span>
                                        <span><i className="ri-time-line me-1">5 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Item 4 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news4} alt="Adventure Post" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">Adventure</small>
                                    <h6 className="blog-section_card-title">
                                        Skydiving Over the Alps: Thrills Above the Clouds
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>David Brown</span>
                                        <span><i className="ri-time-line me-1">10 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Item 5 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news5} alt="Lifestyle Post" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">Lifestyle</small>
                                    <h6 className="blog-section_card-title">
                                        Digital Detox: Reconnecting with Nature and Yourself
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>Olivia Wilson</span>
                                        <span><i className="ri-time-line me-1">7 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Item 6 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news6} alt="Tech Post" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">Technology</small>
                                    <h6 className="blog-section_card-title">
                                        AI in Travel: How Algorithms Plan Your Perfect Trip
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>James Taylor</span>
                                        <span><i className="ri-time-line me-1">9 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Item 7 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news7} alt="Wellness Post" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">Wellness</small>
                                    <h6 className="blog-section_card-title">
                                        Yoga Retreats: Finding Inner Peace While Traveling Abroad
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>Sophia Martinez</span>
                                        <span><i className="ri-time-line me-1">4 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Item 8 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news8} alt="Photography Post" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">Photography</small>
                                    <h6 className="blog-section_card-title">
                                        Capturing Golden Hour: Essential Tips for Travel Photographers
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>Daniel Kim</span>
                                        <span><i className="ri-time-line me-1">6 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Item 9 */}
                        <div className="col-md-4 mb-4">
                            <div className="blog-section_card rounded overflow-hidden">
                                <img src={news9} alt="History Post" className='img-fluid' />
                                <div className="p-3">
                                    <small className="blog-section_small-text">History</small>
                                    <h6 className="blog-section_card-title">
                                        Ancient Ruins: Walking Through Thousands of Years of History
                                    </h6>
                                    <div className="d-flex justify-content-between blog-section_meta small">
                                        <span><i className="ri-user-line me-1"></i>Rachel Green</span>
                                        <span><i className="ri-time-line me-1">12 Min Read</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </>
    )
}

export default BlogSection
