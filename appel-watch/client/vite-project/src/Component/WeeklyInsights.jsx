// src/components/WeeklyInsights.jsx
import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useSelector } from 'react-redux';

const WeeklyInsights = () => {
  const { token } = useSelector((state) => state.auth);
  const [insights, setInsights] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchInsights = async () => {
      try {
        const res = await axios.get('http://127.0.0.1:8000/api/weekly-insights', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setInsights(res.data.insights);
      } catch (err) {
        console.error(err);
        setError('Failed to fetch insights.');
      } finally {
        setLoading(false);
      }
    };

    fetchInsights();
  }, [token]);

  if (loading) return <p>⏳ Loading weekly insights...</p>;
  if (error) return <p style={{ color: 'red' }}>{error}</p>;
  if (!insights || insights.length === 0) return <p>No insights available.</p>;

  return (
    <div style={{ padding: '1rem', border: '1px solid #ccc', borderRadius: '8px', marginTop: '1rem' }}>
      {insights.map((entry) => (
        <div key={entry.id} style={{ marginBottom: '1.5rem', backgroundColor: '#f9f9f9', padding: '1rem', borderRadius: '8px' }}>
          <h3>🗓️ Entry from {new Date(entry.created_at).toLocaleDateString()}</h3>
          <p><strong>User ID:</strong> {entry.user_id}</p>
          <p><strong>Average Steps:</strong> {entry.average_steps}</p>
          <p><strong>Average Distance (km):</strong> {entry.average_distance_km}</p>
          <p><strong>Average Active Minutes:</strong> {entry.average_active_minutes}</p>
          <p><strong>Step Increase Probability:</strong> {entry.step_increase_probability}</p>
          <p><strong>Distance Increase Probability:</strong> {entry.distance_increase_probability}</p>
          <p><strong>Active Minutes Increase Probability:</strong> {entry.active_minutes_increase_probability}</p>
          <p><strong>Focus Area:</strong> {entry.focus_area}</p>

          <p><strong>Recommendations:</strong></p>
          <ul>
            {entry.recommendations.map((rec, idx) => (
              <li key={idx}>{rec}</li>
            ))}
          </ul>

          {entry.raw_json?.actionable_insights && (
            <div>
              <p><strong>🔍 Raw Insights Focus:</strong> {entry.raw_json.actionable_insights.focus_area}</p>
              {/* <p><strong>🔁 Raw Recommendations:</strong></p>
              <ul>
                {entry.raw_json.actionable_insights.recommendations.map((rec, idx) => (
                  <li key={idx}>{rec}</li>
                ))}
              </ul> */}
            </div>
          )}
        </div>
      ))}
    </div>
  );
};

export default WeeklyInsights;
