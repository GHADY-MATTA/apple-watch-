// src/pages/ProfilePage.jsx
import React from 'react';
import { useSelector } from 'react-redux';
import UploadCSV from './UploadCSV';
import WeeklyInsights from '../Component/WeeklyInsights';
import MonthlyInsights from '../Component/MonthlyInsights';
import CycleInsights from '../Component/CycleInsights';


const ProfilePage = () => {
  const user = useSelector((state) => state.auth.user);

  if (!user) return <p>Loading user...</p>;

  return (
    <div style={{ padding: '2rem', fontFamily: 'Arial, sans-serif' }}>
      <h1>👋 Welcome, {user.name}!</h1>
      <p><strong>Email:</strong> {user.email}</p>
          <p><strong>User ID:</strong> {user.id}</p>
          <div>
             <h2>📁 Upload your Health CSV</h2>
      <UploadCSV />
              
          </div>
          <div>
  <h2>📁 Weekly Data</h2>
  <WeeklyInsights />
      </div>
      <div>
  <h2>📁 Monthly Data</h2>
  <MonthlyInsights />
</div>
          <div>
  <h2>📁 Cycle(3M) Data</h2>
  <CycleInsights />
</div>
          
    </div>
    
      
  );
};

export default ProfilePage;
