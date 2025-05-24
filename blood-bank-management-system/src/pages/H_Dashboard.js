import React, { useState } from 'react';
import { useNavigate } from "react-router-dom";

const HospitalDashboard = () => {
  const navigate = useNavigate();
  const [bloodType, setBloodType] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [urgency, setUrgency] = useState('normal');
  const [successMessage, setSuccessMessage] = useState('');

  // Handle blood request submission
  const handleRequest = () => {
    if (!bloodType) {
      alert("Please select a blood type");
      return;
    }
    
    setSuccessMessage(`Blood Request Sent: ${quantity} units of ${bloodType} (${urgency} urgency)`);
    setTimeout(() => setSuccessMessage(''), 5000); // Clear message after 5 seconds
  };

  // Simple Header component matching Home page
  const Header = () => (
    <header style={{
      backgroundColor: "#fff",
      boxShadow: "0 2px 4px rgba(0,0,0,0.1)",
      padding: "1rem",
      width: "100%"
    }}>
      <div style={{
        display: "flex",
        justifyContent: "space-between",
        alignItems: "center",
        maxWidth: "1200px",
        margin: "0 auto"
      }}>
        <div style={{ display: "flex", alignItems: "center" }}>
          <span style={{ color: "#dc2626", fontSize: "24px" }}>❤</span>
          <span style={{ marginLeft: "8px", fontWeight: "bold", fontSize: "18px" }}>BloodDonate</span>
        </div>
        <nav>
          <a href="/" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Home</a>
          <a href="/about" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>About</a>
          <a href="/ourteam" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>OurTeam</a>
          <a href="/contact" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Contact</a>
        </nav>
      </div>
    </header>
  );

  return (
    <div style={{
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      minHeight: "100vh",
      backgroundColor: "#f3f4f6"
    }}>
      {/* Include the Header */}
      <Header />
      
      {/* Main Content */}
      <div style={{
        width: "100%",
        maxWidth: "64rem",
        padding: "0 1.5rem"
      }}>
        {/* Dashboard Header */}
        <div style={{
          backgroundColor: "#fee2e2",
          borderRadius: "0.5rem",
          padding: "1.5rem",
          margin: "2rem 0",
          display: "flex",
          justifyContent: "space-between",
          alignItems: "center",
          boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
        }}>
          <div>
            <h1 style={{
              fontSize: "1.875rem",
              fontWeight: "700",
              color: "#dc2626",
              marginBottom: "0.5rem"
            }}>Hospital Dashboard</h1>
            <p style={{ color: "#4b5563" }}>Manage blood requests and inventory</p>
          </div>
          
          <button
            onClick={() => navigate("/")}
            style={{
              backgroundColor: "white",
              color: "#4b5563",
              border: "1px solid #d1d5db",
              borderRadius: "0.375rem",
              padding: "0.625rem 1.25rem",
              fontSize: "0.875rem",
              cursor: "pointer",
              transition: "all 0.3s ease"
            }}
            onMouseOver={(e) => {
              e.currentTarget.style.backgroundColor = "#f3f4f6";
              e.currentTarget.style.color = "#1f2937";
            }}
            onMouseOut={(e) => {
              e.currentTarget.style.backgroundColor = "white";
              e.currentTarget.style.color = "#4b5563";
            }}
          >
            Logout
          </button>
        </div>

        {/* Success Message */}
        {successMessage && (
          <div style={{
            backgroundColor: "#dcfce7",
            color: "#166534",
            padding: "1rem",
            borderRadius: "0.375rem",
            marginBottom: "1.5rem",
            fontSize: "0.875rem",
            textAlign: "center"
          }}>
            {successMessage}
          </div>
        )}

        {/* Blood Request Form */}
        <div style={{
          backgroundColor: "white",
          borderRadius: "0.5rem",
          padding: "2rem",
          marginBottom: "2rem",
          boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
        }}>
          <h2 style={{
            fontSize: "1.5rem",
            fontWeight: "700",
            color: "#1f2937",
            marginBottom: "1.5rem"
          }}>
            Request Blood Units
          </h2>

          <div style={{
            display: "grid",
            gridTemplateColumns: "repeat(auto-fill, minmax(250px, 1fr))",
            gap: "1.5rem",
            marginBottom: "2rem"
          }}>
            {/* Blood Type Selection */}
            <div>
              <label style={{
                display: "block",
                marginBottom: "0.5rem",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#4b5563"
              }}>
                Blood Type
              </label>
              <select
                value={bloodType}
                onChange={(e) => setBloodType(e.target.value)}
                style={{
                  width: "100%",
                  padding: "0.75rem 1rem",
                  borderRadius: "0.375rem",
                  border: "1px solid #d1d5db",
                  backgroundColor: "white",
                  fontSize: "1rem"
                }}
              >
                <option value="">Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
              </select>
            </div>

            {/* Quantity Input */}
            <div>
              <label style={{
                display: "block",
                marginBottom: "0.5rem",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#4b5563"
              }}>
                Quantity (units)
              </label>
              <input
                type="number"
                min="1"
                value={quantity}
                onChange={(e) => setQuantity(Math.max(1, parseInt(e.target.value) || 1))}
                style={{
                  width: "100%",
                  padding: "0.75rem 1rem",
                  borderRadius: "0.375rem",
                  border: "1px solid #d1d5db",
                  fontSize: "1rem"
                }}
              />
            </div>

            {/* Urgency Selection */}
            <div>
              <label style={{
                display: "block",
                marginBottom: "0.5rem",
                fontSize: "0.875rem",
                fontWeight: "500",
                color: "#4b5563"
              }}>
                Urgency Level
              </label>
              <select
                value={urgency}
                onChange={(e) => setUrgency(e.target.value)}
                style={{
                  width: "100%",
                  padding: "0.75rem 1rem",
                  borderRadius: "0.375rem",
                  border: "1px solid #d1d5db",
                  backgroundColor: "white",
                  fontSize: "1rem"
                }}
              >
                <option value="normal">Normal</option>
                <option value="urgent">Urgent</option>
                <option value="critical">Critical</option>
              </select>
            </div>
          </div>

          {/* Submit Button */}
          <button
            onClick={handleRequest}
            style={{
              backgroundColor: "#dc2626",
              color: "white",
              padding: "0.75rem 1.5rem",
              borderRadius: "0.375rem",
              fontSize: "1rem",
              fontWeight: "600",
              border: "none",
              cursor: "pointer",
              transition: "all 0.3s ease",
              boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
            }}
            onMouseOver={(e) => {
              e.currentTarget.style.backgroundColor = "#b91c1c";
              e.currentTarget.style.transform = "translateY(-2px)";
            }}
            onMouseOut={(e) => {
              e.currentTarget.style.backgroundColor = "#dc2626";
              e.currentTarget.style.transform = "translateY(0)";
            }}
          >
            Request Blood
          </button>
        </div>

        {/* Blood Inventory Dashboard */}
        <div style={{
          backgroundColor: "white",
          borderRadius: "0.5rem",
          padding: "2rem",
          marginBottom: "2rem",
          boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
        }}>
          <h2 style={{
            fontSize: "1.5rem",
            fontWeight: "700",
            color: "#1f2937",
            marginBottom: "1.5rem"
          }}>
            Current Blood Inventory
          </h2>

          <div style={{
            overflowX: "auto"
          }}>
            <table style={{
              width: "100%",
              borderCollapse: "collapse"
            }}>
              <thead>
                <tr style={{ backgroundColor: "#f3f4f6" }}>
                  <th style={{ padding: "0.75rem 1rem", textAlign: "left", borderBottom: "1px solid #e5e7eb" }}>Blood Type</th>
                  <th style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>Available Units</th>
                  <th style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style={{ padding: "0.75rem 1rem", borderBottom: "1px solid #e5e7eb" }}>A+</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>15</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>
                    <span style={{ 
                      backgroundColor: "#dcfce7", 
                      color: "#166534", 
                      padding: "0.25rem 0.75rem", 
                      borderRadius: "9999px", 
                      fontSize: "0.75rem", 
                      fontWeight: "500" 
                    }}>
                      Sufficient
                    </span>
                  </td>
                </tr>
                <tr>
                  <td style={{ padding: "0.75rem 1rem", borderBottom: "1px solid #e5e7eb" }}>B+</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>8</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>
                    <span style={{ 
                      backgroundColor: "#fef9c3", 
                      color: "#854d0e", 
                      padding: "0.25rem 0.75rem", 
                      borderRadius: "9999px", 
                      fontSize: "0.75rem", 
                      fontWeight: "500" 
                    }}>
                      Low
                    </span>
                  </td>
                </tr>
                <tr>
                  <td style={{ padding: "0.75rem 1rem", borderBottom: "1px solid #e5e7eb" }}>O+</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>3</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>
                    <span style={{ 
                      backgroundColor: "#fee2e2", 
                      color: "#b91c1c", 
                      padding: "0.25rem 0.75rem", 
                      borderRadius: "9999px", 
                      fontSize: "0.75rem", 
                      fontWeight: "500" 
                    }}>
                      Critical
                    </span>
                  </td>
                </tr>
                <tr>
                  <td style={{ padding: "0.75rem 1rem", borderBottom: "1px solid #e5e7eb" }}>AB+</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>12</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>
                    <span style={{ 
                      backgroundColor: "#dcfce7", 
                      color: "#166534", 
                      padding: "0.25rem 0.75rem", 
                      borderRadius: "9999px", 
                      fontSize: "0.75rem", 
                      fontWeight: "500" 
                    }}>
                      Sufficient
                    </span>
                  </td>
                </tr>
                <tr>
                  <td style={{ padding: "0.75rem 1rem", borderBottom: "1px solid #e5e7eb" }}>O-</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>6</td>
                  <td style={{ padding: "0.75rem 1rem", textAlign: "center", borderBottom: "1px solid #e5e7eb" }}>
                    <span style={{ 
                      backgroundColor: "#fef9c3", 
                      color: "#854d0e", 
                      padding: "0.25rem 0.75rem", 
                      borderRadius: "9999px", 
                      fontSize: "0.75rem", 
                      fontWeight: "500" 
                    }}>
                      Low
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      {/* Footer */}
      <footer style={{
        width: "100%",
        backgroundColor: "#1f2937",
        color: "white",
        padding: "2rem 1.5rem",
        marginTop: "auto"
      }}>
        <div style={{
          maxWidth: "64rem",
          margin: "0 auto",
          display: "flex",
          flexDirection: "column",
          alignItems: "center"
        }}>
          <div style={{
            display: "flex",
            alignItems: "center",
            marginBottom: "1.5rem"
          }}>
            <span style={{ color: "#ef4444", fontSize: "24px" }}>❤</span>
            <span style={{ marginLeft: "8px", fontWeight: "bold", fontSize: "18px" }}>BloodDonate</span>
          </div>
          
          <div style={{
            marginBottom: "1.5rem",
            textAlign: "center"
          }}>
            <p>Connecting donors with those in need since 2023</p>
            <p style={{ marginTop: "0.5rem" }}>© 2025 BloodDonate. All rights reserved.</p>
          </div>
          
          <div style={{
            display: "flex",
            gap: "1rem"
          }}>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Privacy</a>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Terms</a>
            <a href="#" style={{ color: "white", textDecoration: "none" }}>Contact</a>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default HospitalDashboard;