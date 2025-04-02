import React, { useState } from 'react';
import axios from 'axios';
import { useSelector } from 'react-redux';

const UploadCSV = () => {
  const { token } = useSelector((state) => state.auth);
  const [file, setFile] = useState(null);
  const [status, setStatus] = useState('');
  const [loading, setLoading] = useState(false); // 🌀 Track loading

  const handleFileChange = (e) => {
    setFile(e.target.files[0]);
    setStatus(''); // Clear old messages when new file selected
  };

  const handleUpload = async (e) => {
    e.preventDefault();
    if (!file) return setStatus('❌ Please select a file.');

    const formData = new FormData();
    formData.append('file', file);

    try {
      setLoading(true); // 🌀 Start loading
      setStatus('');    // Clear status while loading

      const res = await axios.post('http://127.0.0.1:8000/api/upload-csv', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          Authorization: `Bearer ${token}`,
        },
      });

      setStatus(`✅ Upload successful! Rows saved: ${res.data.rows_saved}`);
    } catch (err) {
      console.error(err);
      setStatus('❌ Upload failed.');
    } finally {
      setLoading(false); // ✅ Done loading
    }
  };

  return (
    <div style={{ padding: '20px', maxWidth: '500px' }}>
      <h2>📤 Upload Health CSV</h2>

      <form onSubmit={handleUpload}>
        <input type="file" accept=".csv" onChange={handleFileChange} required />
        <br /><br />
        <button type="submit">Upload CSV</button>
      </form>

      {/* 🌀 Loading message */}
      {loading && <p style={{ color: 'blue' }}>🕐 Please wait while your file uploads...</p>}

      {/* ✅ or ❌ Final status */}
      {!loading && status && <p>{status}</p>}
    </div>
  );
};

export default UploadCSV;
