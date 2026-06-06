import React, { useState, useEffect } from 'react';

import { Link } from "react-router-dom";
import blogApiService from '../../services/blogApi';

function BlogSection() {
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [featuredPosts, setFeaturedPosts] = useState([]);
    const [recentPosts, setRecentPosts] = useState([]);
    const [mainFeaturedPost, setMainFeaturedPost] = useState(null);

    useEffect(() => {
        fetchBlogData();
    }, []);

    const fetchBlogData = async () => {
        setLoading(true);
        try {
            const featuredResponse = await blogApiService.getFeaturedPosts(3);
            const postsResponse = await blogApiService.getPosts({ per_page: 9 });
            const allPostsResponse = await blogApiService.getPosts({ per_page: 1 });

            if (featuredResponse.success) {
                setFeaturedPosts(featuredResponse.data);
            }

            if (postsResponse.success) {
                setRecentPosts(postsResponse.data.data || []);
            }

            if (allPostsResponse.success && allPostsResponse.data.data.length > 0) {
                setMainFeaturedPost(allPostsResponse.data.data[0]);
            }

        } catch (err) {
            console.error('Error fetching blog data:', err);
            setError('Không thể tải dữ liệu blog');
        } finally {
            setLoading(false);
        }
    };

    return (
        <>
            <div className="blog-section main-wrapper text-white">
                <div className="container">
                    <div className="row align-items-stretch">
                        {/* Cột trái - Ảnh chính */}
                        <div className="col-lg-6 mb-4 d-flex">
                            <div className="w-100">
                                {mainFeaturedPost && mainFeaturedPost.thumbnail && (
                                    <img 
                                        src={mainFeaturedPost.thumbnail} 
                                        alt={mainFeaturedPost.title} 
                                        className='blog-section_main-img img-fluid rounded w-100' 
                                        style={{ height: '100%', objectFit: 'cover', minHeight: '350px' }}
                                    />
                                )}
                            </div>
                        </div>

                        {/* Cột phải - 3 bài viết featured */}
                        <div className="col-lg-6 mb-4">
                            <div className="d-flex flex-column h-100 justify-content-between">
                                {featuredPosts.map((post, index) => (
                                    <div className="blog-section_post mb-4" key={post.id} style={{ flex: 1 }}>
                                        <small className="blog-section_small-text">
                                            {post.category?.name || 'Tour Guide'}
                                        </small>
                                        <h6 className="blog-section_card-title">
                                            {post.title}
                                        </h6>
                                        <div className="d-flex justify-content-between blog-section_meta small">
                                            <span>{post.author?.name || 'Anonymous'}</span>
                                            <span>
                                                <i className="ri-time-line me-1"></i>
                                                {post.read_time || '10'} Min Read
                                            </span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Hàng dưới - 9 bài viết gần đây */}
                    <div className="row pt-4">
                        {recentPosts.map((post) => (
                            <div className="col-md-4 mb-4" key={post.id}>
                                <div className="blog-section_card rounded overflow-hidden h-100 d-flex flex-column">
                                    <img 
                                        src={post.thumbnail || 'https://via.placeholder.com/400x250'} 
                                        alt={post.title} 
                                        className='img-fluid w-100' 
                                        style={{ height: '200px', objectFit: 'cover' }}
                                    />
                                    <div className="p-3 flex-grow-1 d-flex flex-column">
                                        <small className="blog-section_small-text">
                                            {post.category?.name || 'Travel'}
                                        </small>
                                        <h6 className="blog-section_card-title flex-grow-1">
                                            {post.title}
                                        </h6>
                                        <div className="d-flex justify-content-between blog-section_meta small mt-auto">
                                            <span>
                                                <i className="ri-user-line me-1"></i>
                                                {post.author?.name || 'Anonymous'}
                                            </span>
                                            <span>
                                                <i className="ri-time-line me-1"></i>
                                                {post.read_time || '5'} Min Read
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </>
    );
}

export default BlogSection;