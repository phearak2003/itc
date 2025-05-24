import { useNavigate } from "react-router-dom";
import React from "react";

const DonorLanding = () => {
  const navigate = useNavigate();

  // Simple Header component
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
          <a href="/" style={{ 
            margin: "0 10px", 
            color: "#374151", 
            textDecoration: "none" 
          }}>Home</a>
          <a href="/about" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>About</a>
          <a href="/ourteam" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>OurTeam</a>
          <a href="/contact" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Contact</a>
        </nav>
      </div>
    </header>
  );

  // Banner Component
  const Banner = () => (
    <div style={{
      width: "100%",
      maxWidth: "1200px",
      background: "linear-gradient(to right, #fee2e2, #fecaca)",
      borderRadius: "8px",
      padding: "2rem",
      margin: "2rem 0",
      boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
    }}>
      <div style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "center",
        textAlign: "center"
      }}>
        <h2 style={{
          fontSize: "1.5rem",
          fontWeight: "600",
          color: "#dc2626",
          marginBottom: "1rem"
        }}>Welcome Donor! 🩸</h2>
        <p style={{
          fontSize: "1.125rem",
          color: "#4b5563",
          maxWidth: "800px"
        }}>
          Thank you for choosing to save lives. Your donation can help save up to three lives.
          Please sign in or register to continue your journey of making a difference.
        </p>
      </div>
    </div>
  );

  return (
    <div style={{
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      justifyContent: "center",
      minHeight: "100vh",
      backgroundColor: "#f3f4f6",
      padding: "0 1.5rem"
    }}>
      {/* Top Components */}
      <Header />
      <Banner />

      {/* Main Content */}
      <div style={{
        width: "100%",
        maxWidth: "64rem",
        textAlign: "center",
        marginTop: "2rem"
      }}>
        <h1 style={{
          fontSize: "3rem",
          fontWeight: "800",
          color: "#dc2626",
          marginBottom: "1.5rem"
        }}>
          Donor Portal
        </h1>
        <p style={{
          fontSize: "1.125rem",
          color: "#4b5563",
          marginBottom: "2rem"
        }}>
          Please login or register to access your donor dashboard.
        </p>

        {/* Role Selection Section */}
        <div style={{
          display: "flex",
          flexWrap: "wrap",
          justifyContent: "center",
          gap: "1.5rem"
        }}>
          <button
            style={{
              width: "16rem",
              backgroundColor: "#ef4444",
              color: "white",
              padding: "0.75rem 0",
              borderRadius: "0.75rem",
              fontSize: "1.125rem",
              fontWeight: "600",
              boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
              border: "none",
              cursor: "pointer",
              transition: "transform 0.3s ease",
            }}
            onMouseOver={(e) => e.currentTarget.style.transform = "scale(1.1)"}
            onMouseOut={(e) => e.currentTarget.style.transform = "scale(1)"}
            onClick={() => navigate("/donor-login")}
          >
            🔐 Login
          </button>
          
          <button
            style={{
              width: "16rem",
              backgroundColor: "#3b82f6",
              color: "white",
              padding: "0.75rem 0",
              borderRadius: "0.75rem",
              fontSize: "1.125rem",
              fontWeight: "600",
              boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
              border: "none",
              cursor: "pointer",
              transition: "transform 0.3s ease",
            }}
            onMouseOver={(e) => e.currentTarget.style.transform = "scale(1.1)"}
            onMouseOut={(e) => e.currentTarget.style.transform = "scale(1)"}
            onClick={() => navigate("/donor-register")}
          >
            📝 Register
          </button>
        </div>
      </div>
      
      {/* Additional Features Section */}
      <div style={{
        width: "100%",
        maxWidth: "64rem",
        marginTop: "4rem",
        marginBottom: "2rem"
      }}>
        <h2 style={{
          fontSize: "1.875rem",
          fontWeight: "700",
          color: "#1f2937",
          textAlign: "center",
          marginBottom: "2rem"
        }}>
          Why Become a Donor?
        </h2>
        
        <div style={{
          display: "grid",
          gridTemplateColumns: "repeat(auto-fit, minmax(250px, 1fr))",
          gap: "1.5rem"
        }}>
          <div style={{
            backgroundColor: "white",
            borderRadius: "0.5rem",
            padding: "1.5rem",
            boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
          }}>
            <div style={{
              fontSize: "2rem",
              marginBottom: "1rem",
              textAlign: "center"
            }}>❤️</div>
            <h3 style={{
              fontSize: "1.25rem",
              fontWeight: "600",
              marginBottom: "0.75rem",
              textAlign: "center"
            }}>Save Lives</h3>
            <p style={{
              color: "#4b5563",
              textAlign: "center"
            }}>
              Your blood donation can save up to three lives in emergency situations.
            </p>
          </div>
          
          <div style={{
            backgroundColor: "white",
            borderRadius: "0.5rem",
            padding: "1.5rem",
            boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
          }}>
            <div style={{
              fontSize: "2rem",
              marginBottom: "1rem",
              textAlign: "center"
            }}>📱</div>
            <h3 style={{
              fontSize: "1.25rem",
              fontWeight: "600",
              marginBottom: "0.75rem",
              textAlign: "center"
            }}>Track Donations</h3>
            <p style={{
              color: "#4b5563",
              textAlign: "center"
            }}>
              Monitor your donation history and impact on lives saved.
            </p>
          </div>
          
          <div style={{
            backgroundColor: "white",
            borderRadius: "0.5rem",
            padding: "1.5rem",
            boxShadow: "0 4px 6px rgba(0,0,0,0.1)"
          }}>
            <div style={{
              fontSize: "2rem",
              marginBottom: "1rem",
              textAlign: "center"
            }}>🔔</div>
            <h3 style={{
              fontSize: "1.25rem",
              fontWeight: "600",
              marginBottom: "0.75rem",
              textAlign: "center"
            }}>Donation Alerts</h3>
            <p style={{
              color: "#4b5563",
              textAlign: "center"
            }}>
              Receive notifications when your blood type is urgently needed.
            </p>
          </div>
        </div>
      </div>
      
      {/* Statistics Section */}
      <div style={{
        width: "100%",
        backgroundColor: "#dc2626",
        padding: "3rem 1.5rem",
        color: "white",
        marginTop: "2rem"
      }}>
        <div style={{
          maxWidth: "64rem",
          margin: "0 auto",
          textAlign: "center"
        }}>
          <h2 style={{
            fontSize: "1.875rem",
            fontWeight: "700",
            marginBottom: "2rem"
          }}>Our Impact</h2>
          
          <div style={{
            display: "grid",
            gridTemplateColumns: "repeat(auto-fit, minmax(200px, 1fr))",
            gap: "2rem"
          }}>
            <div>
              <div style={{
                fontSize: "2.5rem",
                fontWeight: "800",
                marginBottom: "0.5rem"
              }}>10,000+</div>
              <div>Successful Donations</div>
            </div>
            
            <div>
              <div style={{
                fontSize: "2.5rem",
                fontWeight: "800",
                marginBottom: "0.5rem"
              }}>30,000+</div>
              <div>Lives Saved</div>
            </div>
            
            <div>
              <div style={{
                fontSize: "2.5rem",
                fontWeight: "800",
                marginBottom: "0.5rem"
              }}>500+</div>
              <div>Partner Hospitals</div>
            </div>
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

export default DonorLanding;