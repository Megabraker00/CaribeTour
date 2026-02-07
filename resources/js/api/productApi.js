import axios from 'axios';

const API_BASE_URL = '/api/v1/products';

export const fetchProducts = async () => {
    try {
        const response = await axios.get(API_BASE_URL);
        return response.data;
    } catch (error) {
        console.error('Error fetching products:', error);
        throw error;
    }
};

export const fetchProductById = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error fetching product with id ${id}:`, error);
        throw error;
    }
};

export const createProduct = async (productData) => {
    try {
        const response = await axios.post(API_BASE_URL, productData);
        return response.data;
    } catch (error) {
        console.error('Error creating product:', error);
        throw error;
    }
};

export const updateProduct = async (id, productData) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/${id}`, productData);
        return response.data;
    } catch (error) {
        console.error(`Error updating product with id ${id}:`, error);
        throw error;
    }
};

export const deleteProduct = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error deleting product with id ${id}:`, error);
        throw error;
    }
};