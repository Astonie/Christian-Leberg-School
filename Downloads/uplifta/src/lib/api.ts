import axios from "axios";

export const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || "http://localhost:3001", // configurable
  withCredentials: true,
});

// Attach token if present and basic error logging
api.interceptors.request.use((config) => {
  const token = typeof window !== "undefined" ? localStorage.getItem("uplifta_token") : null;
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});
api.interceptors.response.use(
  (r) => r,
  (error) => {
    if (error?.response?.status === 401) {
      // optionally redirect to login
    }
    return Promise.reject(error);
  }
);

// Example API functions (mocked for now)
export async function fetchDashboardSummary() {
  // Replace with: return api.get('/dashboard/summary').then(r => r.data)
  return Promise.resolve({
    totalLoans: 0,
    totalClients: 0,
    portfolioAtRisk: 0,
    overdue: 0,
  });
}


