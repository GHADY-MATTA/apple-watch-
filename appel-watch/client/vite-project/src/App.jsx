// import { useState } from 'react'
// import reactLogo from './assets/react.svg'
// import viteLogo from '/vite.svg'
import './App.css';
import Login from './pages/login';
import UploadCSV from './pages/UploadCSV';
import WeeklyInsights from './Component/WeeklyInsights';
import MonthlyInsights from './Component/MonthlyInsights';
import CycleInsights from './Component/CycleInsights';
import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
// import LoginPage from './features/auth/LoginPage';
import ProfilePage from './pages/ProfilePage';
import Signup from './pages/Signup';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/profile" element={<ProfilePage />} />
        <Route path="/upload" element={<UploadCSV />} />
        <Route path="/WeeklyInsights" element={<WeeklyInsights />} />
        <Route path="/MonthlyInsights" element={<MonthlyInsights />} />
        <Route path="/CycleInsights" element={<CycleInsights />} />
        <Route path="/Signup" element={<Signup />} />
      </Routes>
    </BrowserRouter>
  );
}
export default App;
