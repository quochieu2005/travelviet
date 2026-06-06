import { get, post } from './api';

// Get pricing plans
export const getPricingPlans = async () => {
  try {
    const data = await get('/pricing-plans');
    return data;
  } catch (error) {
    console.error('Error fetching pricing plans:', error);
    return { success: false, message: error.message };
  }
};

// Submit pricing inquiry
export const submitPricingInquiry = async (inquiryData) => {
  try {
    const data = await post('/pricing-inquiries', inquiryData);
    return data;
  } catch (error) {
    console.error('Error submitting inquiry:', error);
    return { success: false, message: error.message };
  }
};