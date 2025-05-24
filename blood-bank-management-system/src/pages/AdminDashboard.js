import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

const AdminDashboard = () => {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [stats, setStats] = useState({
    donors: 132,
    hospitals: 8,
    bloodUnits: 245
  });

  useEffect(() => {
    // Check authentication status
    const isAdmin = localStorage.getItem("adminAuth");
    if (!isAdmin) {
      navigate("/admin-login"); // Redirect if not logged in
      return;
    }
    
    // Simulate data loading
    const timer = setTimeout(() => {
      setLoading(false);
    }, 500);
    
    return () => clearTimeout(timer);
  }, [navigate]);

  const handleLogout = () => {
    localStorage.removeItem("adminAuth");
    navigate("/admin-login");
  };

  // Header component to match styling with other pages
  const Header = () => (
    <header style={{
      backgroundColor: "white",
      boxShadow: "0 1px 2px 0 rgba(0, 0, 0, 0.05)",
      width: "100%",
      marginBottom: "1.5rem"
    }}>
      <div style={{
        maxWidth: "80rem",
        margin: "0 auto",
        padding: "1rem 1.5rem",
        display: "flex",
        justifyContent: "space-between",
        alignItems: "center"
      }}>
        <div style={{ display: "flex", alignItems: "center" }}>
          <span style={{ fontSize: "1.5rem", color: "#dc2626" }}>❤</span>
          <span style={{ marginLeft: "0.5rem", fontWeight: "bold", fontSize: "1.125rem" }}>BloodDonate</span>
          <span style={{ marginLeft: "1.5rem", fontSize: "1.5rem", fontWeight: "bold", color: "#dc2626" }}>Admin Dashboard</span>
        </div>
        <div style={{ display: "flex", alignItems: "center", gap: "1rem" }}>
          <button
            onClick={handleLogout}
            style={{
              backgroundColor: "#ef4444",
              color: "white",
              padding: "0.5rem 1rem",
              borderRadius: "0.5rem",
              border: "none",
              cursor: "pointer",
              transition: "background-color 0.3s",
            }}
            onMouseOver={(e) => e.currentTarget.style.backgroundColor = "#dc2626"}
            onMouseOut={(e) => e.currentTarget.style.backgroundColor = "#ef4444"}
          >
            Logout
          </button>
        </div>
      </div>
    </header>
  );

  // Loading state
  if (loading) {
    return (
      <div style={{
        minHeight: "100vh",
        backgroundColor: "#f3f4f6",
        display: "flex",
        justifyContent: "center",
        alignItems: "center"
      }}>
        <div style={{ textAlign: "center" }}>
          <div style={{
            display: "inline-block",
            height: "2rem",
            width: "2rem",
            animation: "spin 1s linear infinite",
            borderRadius: "50%",
            borderWidth: "4px",
            borderStyle: "solid",
            borderColor: "#dc2626 transparent #dc2626 transparent",
          }}></div>
          <p style={{ marginTop: "0.5rem", color: "#4b5563" }}>Loading dashboard...</p>
        </div>
        <style>
          {`
            @keyframes spin {
              0% { transform: rotate(0deg); }
              100% { transform: rotate(360deg); }
            }
          `}
        </style>
      </div>
    );
  }

  return (
    <div style={{ minHeight: "100vh", backgroundColor: "#f3f4f6", display: "flex", flexDirection: "column" }}>
      <Header />
      
      <main style={{ flexGrow: 1, maxWidth: "1280px", margin: "0 auto", padding: "1.5rem 1rem" }}>
        {/* Stats Cards */}
        <div style={{ 
          display: "grid", 
          gridTemplateColumns: "repeat(auto-fit, minmax(300px, 1fr))", 
          gap: "1.5rem", 
          marginBottom: "2rem" 
        }}>
          <div style={{
            backgroundColor: "white",
            boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
            borderRadius: "0.5rem",
            padding: "1.5rem",
            transition: "box-shadow 0.3s",
          }}
          onMouseOver={(e) => e.currentTarget.style.boxShadow = "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)"}
          onMouseOut={(e) => e.currentTarget.style.boxShadow = "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)"}
          >
            <h2 style={{ fontSize: "1.25rem", fontWeight: 600, marginBottom: "0.5rem" }}>👥 Total Donors</h2>
            <p style={{ fontSize: "1.875rem", fontWeight: "bold", color: "#3b82f6" }}>{stats.donors}</p>
            <p style={{ fontSize: "0.875rem", color: "#6b7280", marginTop: "0.5rem" }}>+12 new this month</p>
          </div>
          <div style={{
            backgroundColor: "white",
            boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
            borderRadius: "0.5rem",
            padding: "1.5rem",
            transition: "box-shadow 0.3s",
          }}
          onMouseOver={(e) => e.currentTarget.style.boxShadow = "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)"}
          onMouseOut={(e) => e.currentTarget.style.boxShadow = "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)"}
          >
            <h2 style={{ fontSize: "1.25rem", fontWeight: 600, marginBottom: "0.5rem" }}>🏥 Total Hospitals</h2>
            <p style={{ fontSize: "1.875rem", fontWeight: "bold", color: "#10b981" }}>{stats.hospitals}</p>
            <p style={{ fontSize: "0.875rem", color: "#6b7280", marginTop: "0.5rem" }}>+1 new this month</p>
          </div>
          <div style={{
            backgroundColor: "white",
            boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
            borderRadius: "0.5rem",
            padding: "1.5rem",
            transition: "box-shadow 0.3s",
          }}
          onMouseOver={(e) => e.currentTarget.style.boxShadow = "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)"}
          onMouseOut={(e) => e.currentTarget.style.boxShadow = "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)"}
          >
            <h2 style={{ fontSize: "1.25rem", fontWeight: 600, marginBottom: "0.5rem" }}>🩸 Blood Units</h2>
            <p style={{ fontSize: "1.875rem", fontWeight: "bold", color: "#ef4444" }}>{stats.bloodUnits}</p>
            <p style={{ fontSize: "0.875rem", color: "#6b7280", marginTop: "0.5rem" }}>+28 this month</p>
          </div>
        </div>
        
        {/* Recent Activity */}
        <div style={{
          backgroundColor: "white", 
          boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
          borderRadius: "0.5rem",
          padding: "1.5rem",
          marginBottom: "2rem"
        }}>
          <h2 style={{ fontSize: "1.25rem", fontWeight: 600, marginBottom: "1rem" }}>Recent Activity</h2>
          <div style={{ overflowX: "auto" }}>
            <table style={{ minWidth: "100%", borderCollapse: "separate", borderSpacing: 0 }}>
              <thead style={{ backgroundColor: "#f9fafb" }}>
                <tr>
                  <th style={{ padding: "0.75rem 1.5rem", textAlign: "left", fontSize: "0.75rem", fontWeight: 500, color: "#6b7280", textTransform: "uppercase", letterSpacing: "0.05em" }}>Date</th>
                  <th style={{ padding: "0.75rem 1.5rem", textAlign: "left", fontSize: "0.75rem", fontWeight: 500, color: "#6b7280", textTransform: "uppercase", letterSpacing: "0.05em" }}>Event</th>
                  <th style={{ padding: "0.75rem 1.5rem", textAlign: "left", fontSize: "0.75rem", fontWeight: 500, color: "#6b7280", textTransform: "uppercase", letterSpacing: "0.05em" }}>Details</th>
                  <th style={{ padding: "0.75rem 1.5rem", textAlign: "left", fontSize: "0.75rem", fontWeight: 500, color: "#6b7280", textTransform: "uppercase", letterSpacing: "0.05em" }}>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr style={{ borderBottom: "1px solid #e5e7eb" }}>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", color: "#6b7280" }}>May 14, 2025</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", fontWeight: 500, color: "#111827" }}>Blood Donation</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", color: "#6b7280" }}>John Doe (A+)</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap" }}>
                    <span style={{ 
                      padding: "0.25rem 0.5rem", 
                      display: "inline-flex", 
                      fontSize: "0.75rem", 
                      fontWeight: "600", 
                      borderRadius: "9999px", 
                      backgroundColor: "#dcfce7", 
                      color: "#166534" 
                    }}>Completed</span>
                  </td>
                </tr>
                <tr style={{ borderBottom: "1px solid #e5e7eb" }}>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", color: "#6b7280" }}>May 13, 2025</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", fontWeight: 500, color: "#111827" }}>New Hospital</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", color: "#6b7280" }}>City Medical Center</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap" }}>
                    <span style={{ 
                      padding: "0.25rem 0.5rem", 
                      display: "inline-flex", 
                      fontSize: "0.75rem", 
                      fontWeight: "600", 
                      borderRadius: "9999px", 
                      backgroundColor: "#dbeafe", 
                      color: "#1e40af" 
                    }}>Registered</span>
                  </td>
                </tr>
                <tr style={{ borderBottom: "1px solid #e5e7eb" }}>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", color: "#6b7280" }}>May 12, 2025</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", fontWeight: 500, color: "#111827" }}>Blood Request</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap", fontSize: "0.875rem", color: "#6b7280" }}>Memorial Hospital (O-)</td>
                  <td style={{ padding: "1rem 1.5rem", whiteSpace: "nowrap" }}>
                    <span style={{ 
                      padding: "0.25rem 0.5rem", 
                      display: "inline-flex", 
                      fontSize: "0.75rem", 
                      fontWeight: "600", 
                      borderRadius: "9999px", 
                      backgroundColor: "#fef9c3", 
                      color: "#854d0e" 
                    }}>Pending</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
        {/* Quick Actions and Blood Stock */}
        <div style={{ 
          display: "grid", 
          gridTemplateColumns: "repeat(auto-fit, minmax(300px, 1fr))", 
          gap: "1.5rem" 
        }}>
          <div style={{
            backgroundColor: "white",
            boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
            borderRadius: "0.5rem",
            padding: "1.5rem"
          }}>
            <h2 style={{ fontSize: "1.25rem", fontWeight: 600, marginBottom: "1rem" }}>Quick Actions</h2>
            <div style={{ display: "flex", flexDirection: "column", gap: "0.5rem" }}>
              <button style={{
                backgroundColor: "#3b82f6",
                color: "white",
                padding: "0.5rem 1rem",
                borderRadius: "0.25rem",
                border: "none",
                cursor: "pointer",
                transition: "background-color 0.3s"
              }}
              onMouseOver={(e) => e.currentTarget.style.backgroundColor = "#2563eb"}
              onMouseOut={(e) => e.currentTarget.style.backgroundColor = "#3b82f6"}
              >
                Add New Donor
              </button>
              <button style={{
                backgroundColor: "#10b981",
                color: "white",
                padding: "0.5rem 1rem",
                borderRadius: "0.25rem",
                border: "none",
                cursor: "pointer",
                transition: "background-color 0.3s"
              }}
              onMouseOver={(e) => e.currentTarget.style.backgroundColor = "#059669"}
              onMouseOut={(e) => e.currentTarget.style.backgroundColor = "#10b981"}
              >
                Register Hospital
              </button>
              <button style={{
                backgroundColor: "#8b5cf6",
                color: "white",
                padding: "0.5rem 1rem",
                borderRadius: "0.25rem",
                border: "none",
                cursor: "pointer",
                transition: "background-color 0.3s"
              }}
              onMouseOver={(e) => e.currentTarget.style.backgroundColor = "#7c3aed"}
              onMouseOut={(e) => e.currentTarget.style.backgroundColor = "#8b5cf6"}
              >
                Manage Blood Inventory
              </button>
            </div>
          </div>
          
          <div style={{
            backgroundColor: "white",
            boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
            borderRadius: "0.5rem",
            padding: "1.5rem"
          }}>
            <h2 style={{ fontSize: "1.25rem", fontWeight: 600, marginBottom: "1rem" }}>Blood Stock Levels</h2>
            <div style={{ display: "flex", flexDirection: "column", gap: "1rem" }}>
              <div>
                <div style={{ display: "flex", justifyContent: "space-between", marginBottom: "0.25rem" }}>
                  <span style={{ fontSize: "0.875rem", fontWeight: 500 }}>A+ (75 units)</span>
                  <span style={{ fontSize: "0.875rem", fontWeight: 500 }}>75%</span>
                </div>
                <div style={{ width: "100%", backgroundColor: "#e5e7eb", borderRadius: "9999px", height: "0.625rem" }}>
                  <div style={{ backgroundColor: "#10b981", height: "0.625rem", borderRadius: "9999px", width: "75%" }}></div>
                </div>
              </div>
              <div>
                <div style={{ display: "flex", justifyContent: "space-between", marginBottom: "0.25rem" }}>
                  <span style={{ fontSize: "0.875rem", fontWeight: 500 }}>B+ (50 units)</span>
                  <span style={{ fontSize: "0.875rem", fontWeight: 500 }}>50%</span>
                </div>
                <div style={{ width: "100%", backgroundColor: "#e5e7eb", borderRadius: "9999px", height: "0.625rem" }}>
                  <div style={{ backgroundColor: "#eab308", height: "0.625rem", borderRadius: "9999px", width: "50%" }}></div>
                </div>
              </div>
              <div>
                <div style={{ display: "flex", justifyContent: "space-between", marginBottom: "0.25rem" }}>
                  <span style={{ fontSize: "0.875rem", fontWeight: 500 }}>O- (20 units)</span>
                  <span style={{ fontSize: "0.875rem", fontWeight: 500 }}>20%</span>
                </div>
                <div style={{ width: "100%", backgroundColor: "#e5e7eb", borderRadius: "9999px", height: "0.625rem" }}>
                  <div style={{ backgroundColor: "#ef4444", height: "0.625rem", borderRadius: "9999px", width: "20%" }}></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      
      {/* Footer */}
      <footer style={{ backgroundColor: "#1f2937", color: "white", padding: "1.5rem 1rem" }}>
        <div style={{ maxWidth: "80rem", margin: "0 auto", display: "flex", flexDirection: "column", alignItems: "center" }}>
          <div style={{ display: "flex", alignItems: "center", marginBottom: "1rem" }}>
            <span style={{ fontSize: "1.5rem", color: "#ef4444" }}>❤</span>
            <span style={{ marginLeft: "0.5rem", fontWeight: "bold", fontSize: "1.125rem" }}>BloodDonate</span>
          </div>
          <p style={{ textAlign: "center", fontSize: "0.875rem", marginBottom: "1rem" }}>© 2025 BloodDonate Admin Portal. All rights reserved.</p>
          <div style={{ display: "flex", gap: "1rem" }}>
            <a href="/privacy" style={{ 
              color: "#d1d5db", 
              fontSize: "0.875rem", 
              textDecoration: "none",
              transition: "color 0.3s"
            }}
            onMouseOver={(e) => e.currentTarget.style.color = "white"}
            onMouseOut={(e) => e.currentTarget.style.color = "#d1d5db"}
            >Privacy</a>
            <a href="/terms" style={{ 
              color: "#d1d5db", 
              fontSize: "0.875rem", 
              textDecoration: "none",
              transition: "color 0.3s"
            }}
            onMouseOver={(e) => e.currentTarget.style.color = "white"}
            onMouseOut={(e) => e.currentTarget.style.color = "#d1d5db"}
            >Terms</a>
            <a href="/contact" style={{ 
              color: "#d1d5db", 
              fontSize: "0.875rem", 
              textDecoration: "none",
              transition: "color 0.3s"
            }}
            onMouseOver={(e) => e.currentTarget.style.color = "white"}
            onMouseOut={(e) => e.currentTarget.style.color = "#d1d5db"}
            >Contact</a>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default AdminDashboard;