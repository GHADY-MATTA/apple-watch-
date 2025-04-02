import React, { useState, useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { registerUser } from '../features/auth/authSlice';
import { useNavigate } from 'react-router-dom'; // 👈 import this

const Signup = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate(); // 👈 initialize navigation

  const { loading, error, user } = useSelector(state => state.auth);

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: ''
  });

  const handleChange = (e) => {
    setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    dispatch(registerUser(formData));
  };

  // 👇 Redirect to login after successful registration
  useEffect(() => {
    if (user) {
      setTimeout(() => {
        navigate('/'); // 👈 redirect to login page
      }, 1000); // small delay to show success
    }
  }, [user, navigate]);

  return (
    <div style={{ padding: '2rem', fontFamily: 'Arial' }}>
      <h2>🔐 Sign Up</h2>

      {loading && <p>Loading...</p>}
      {error && <p style={{ color: 'red' }}>{error}</p>}
      {user && <p style={{ color: 'green' }}>✅ Account created! Redirecting to login...</p>}

      <form onSubmit={handleSubmit}>
        <input
          type="text"
          name="name"
          placeholder="Full Name"
          onChange={handleChange}
          required
        /><br /><br />
        <input
          type="email"
          name="email"
          placeholder="Email"
          onChange={handleChange}
          required
        /><br /><br />
        <input
          type="password"
          name="password"
          placeholder="Password"
          onChange={handleChange}
          required
        /><br /><br />
        <button type="submit">Create Account</button>
      </form>
    </div>
  );
};

export default Signup;
