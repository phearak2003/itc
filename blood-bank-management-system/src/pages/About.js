import { useNavigate } from "react-router-dom";
import React, { useState } from "react";

const About = () => {
  const navigate = useNavigate();
  const [activeIndex, setActiveIndex] = useState(null);

  const handleCardClick = (index) => {
    setActiveIndex(activeIndex === index ? null : index);
  };

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
        <div style={{ 
          display: "flex", 
          alignItems: "center",
          cursor: "pointer"
        }} onClick={() => navigate("/")}>
          <span style={{ color: "#dc2626", fontSize: "24px" }}>❤</span>
          <span style={{ marginLeft: "8px", fontWeight: "bold", fontSize: "18px" }}>BloodDonate</span>
        </div>
        <nav>
          <a href="/" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Home</a>
          <a href="/about" style={{ 
            margin: "0 10px", 
            color: "#dc2626", 
            fontWeight: "500", 
            textDecoration: "none",
            borderBottom: "2px solid #dc2626",
            paddingBottom: "5px"
          }}>About</a>
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
      justifyContent: "center",
      minHeight: "100vh",
      backgroundColor: "#f3f4f6",
      padding: "0 1.5rem"
    }}>
      {/* Top Components */}
      <Header />

      {/* Hero Section */}
      <div style={{
        background: "linear-gradient(to right, #dc2626, #b91c1c)",
        color: "white",
        padding: "5rem 1rem",
        textAlign: "center",
        width: "100%"
      }}>
        <div style={{ maxWidth: "1200px", margin: "0 auto" }}>
          <h1 style={{ 
            fontSize: "2.5rem", 
            fontWeight: "bold", 
            marginBottom: "1.5rem" 
          }}>Saving Lives Together</h1>
          <p style={{ 
            fontSize: "1.25rem", 
            maxWidth: "800px", 
            margin: "0 auto" 
          }}>
            Our Blood Donation System connects compassionate donors with patients in need,
            creating a network of hope and healing throughout our community.
          </p>
        </div>
      </div>
      
      {/* Mission Section */}
      <div style={{ maxWidth: "64rem", margin: "4rem auto", padding: "0 1rem" }}>
        <div style={{ 
          display: "flex", 
          flexDirection: "column", 
          gap: "2.5rem" 
        }}>
          <div style={{ flex: "1" }}>
            <h2 style={{ 
              fontSize: "1.875rem", 
              fontWeight: "700", 
              marginBottom: "1rem", 
              color: "#1f2937",
              textAlign: "center" 
            }}>Our Mission</h2>
            <p style={{ 
              fontSize: "1.125rem", 
              color: "#4b5563", 
              marginBottom: "1rem" 
            }}>
              We believe that access to safe blood transfusions is a fundamental right. Our mission is to ensure that every hospital 
              and medical facility has the blood supply they need, when they need it.
            </p>
            <p style={{ 
              fontSize: "1.125rem", 
              color: "#4b5563" 
            }}>
              Through innovation, education, and community engagement, we're creating a sustainable blood donation ecosystem that serves 
              everyone in our community, especially those in critical need.
            </p>
          </div>
          
          <div style={{ 
            flex: "1", 
            backgroundColor: "#fef2f2", 
            borderRadius: "0.5rem", 
            padding: "2rem", 
            boxShadow: "0 4px 6px rgba(0,0,0,0.1)" 
          }}>
            <h3 style={{ 
              fontSize: "1.5rem", 
              fontWeight: "600", 
              marginBottom: "1rem", 
              color: "#dc2626" 
            }}>Why Donate Blood?</h3>
            <ul style={{ listStyleType: "none", padding: 0, margin: 0 }}>
              <li style={{ 
                display: "flex", 
                alignItems: "flex-start", 
                marginBottom: "0.75rem" 
              }}>
                <span style={{ color: "#dc2626", marginRight: "0.5rem" }}>💧</span>
                <span style={{ color: "#4b5563" }}>One donation can save up to 3 lives</span>
              </li>
              <li style={{ 
                display: "flex", 
                alignItems: "flex-start", 
                marginBottom: "0.75rem" 
              }}>
                <span style={{ color: "#dc2626", marginRight: "0.5rem" }}>💧</span>
                <span style={{ color: "#4b5563" }}>Critical for surgeries, cancer treatment, chronic illnesses, and trauma cases</span>
              </li>
              <li style={{ 
                display: "flex", 
                alignItems: "flex-start", 
                marginBottom: "0.75rem" 
              }}>
                <span style={{ color: "#dc2626", marginRight: "0.5rem" }}>💧</span>
                <span style={{ color: "#4b5563" }}>Blood has a limited shelf life and needs constant replenishment</span>
              </li>
              <li style={{ 
                display: "flex", 
                alignItems: "flex-start" 
              }}>
                <span style={{ color: "#dc2626", marginRight: "0.5rem" }}>💧</span>
                <span style={{ color: "#4b5563" }}>Less than 10% of eligible donors actually donate</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      
      {/* Stats Section */}
      <div style={{ backgroundColor: "#f9fafb", padding: "4rem 1.5rem", width: "100%" }}>
        <div style={{ maxWidth: "64rem", margin: "0 auto" }}>
          <h2 style={{ 
            fontSize: "1.875rem", 
            fontWeight: "700", 
            textAlign: "center", 
            marginBottom: "3rem", 
            color: "#1f2937" 
          }}>Blood Donation Impact</h2>
          
          <div style={{ 
            display: "grid", 
            gridTemplateColumns: "repeat(auto-fit, minmax(200px, 1fr))", 
            gap: "2rem" 
          }}>
            <div style={{ textAlign: "center" }}>
              <div style={{ fontSize: "2.5rem", marginBottom: "1rem" }}>❤️</div>
              <div style={{ fontSize: "1.5rem", fontWeight: "bold", color: "#1f2937" }}>3 Lives</div>
              <div style={{ fontSize: "0.875rem", color: "#6b7280" }}>Saved per donation</div>
            </div>
            
            <div style={{ textAlign: "center" }}>
              <div style={{ fontSize: "2.5rem", marginBottom: "1rem" }}>💧</div>
              <div style={{ fontSize: "1.5rem", fontWeight: "bold", color: "#1f2937" }}>470ml</div>
              <div style={{ fontSize: "0.875rem", color: "#6b7280" }}>Average donation</div>
            </div>
            
            <div style={{ textAlign: "center" }}>
              <div style={{ fontSize: "2.5rem", marginBottom: "1rem" }}>📅</div>
              <div style={{ fontSize: "1.5rem", fontWeight: "bold", color: "#1f2937" }}>Every 56 days</div>
              <div style={{ fontSize: "0.875rem", color: "#6b7280" }}>Safe donation frequency</div>
            </div>
            
            <div style={{ textAlign: "center" }}>
              <div style={{ fontSize: "2.5rem", marginBottom: "1rem" }}>👥</div>
              <div style={{ fontSize: "1.5rem", fontWeight: "bold", color: "#1f2937" }}>38%</div>
              <div style={{ fontSize: "0.875rem", color: "#6b7280" }}>Of people eligible to donate</div>
            </div>
          </div>
        </div>
      </div>
      
      {/* Benefits Section */}
      <div style={{ maxWidth: "64rem", margin: "4rem auto", padding: "0 1.5rem" }}>
        <h2 style={{ 
          fontSize: "1.875rem", 
          fontWeight: "700", 
          textAlign: "center", 
          marginBottom: "3rem", 
          color: "#1f2937" 
        }}>Benefits of Donating</h2>
        
        <div style={{ 
          display: "grid", 
          gridTemplateColumns: "repeat(auto-fit, minmax(300px, 1fr))", 
          gap: "2rem" 
        }}>
          <div style={{ 
            backgroundColor: "white", 
            padding: "1.5rem", 
            borderRadius: "0.5rem", 
            boxShadow: "0 4px 6px rgba(0,0,0,0.1)" 
          }}>
            <div style={{ 
              display: "flex", 
              alignItems: "center", 
              marginBottom: "1rem" 
            }}>
              <span style={{ fontSize: "1.25rem", marginRight: "0.5rem" }}>✓</span>
              <h3 style={{ fontSize: "1.25rem", fontWeight: "600" }}>Health Benefits</h3>
            </div>
            
            <ul style={{ margin: 0, paddingLeft: "1.5rem" }}>
              <li style={{ color: "#4b5563", marginBottom: "0.5rem" }}>
                Free health screening with each donation
              </li>
              <li style={{ color: "#4b5563", marginBottom: "0.5rem" }}>
                Reduced risk of heart disease
              </li>
              <li style={{ color: "#4b5563", marginBottom: "0.5rem" }}>
                Replenishment of blood cells promotes better health
              </li>
              <li style={{ color: "#4b5563" }}>
                Helps maintain iron levels
              </li>
            </ul>
          </div>
          
          <div style={{ 
            backgroundColor: "white", 
            padding: "1.5rem", 
            borderRadius: "0.5rem", 
            boxShadow: "0 4px 6px rgba(0,0,0,0.1)" 
          }}>
            <div style={{ 
              display: "flex", 
              alignItems: "center", 
              marginBottom: "1rem" 
            }}>
              <span style={{ fontSize: "1.25rem", marginRight: "0.5rem" }}>🏆</span>
              <h3 style={{ fontSize: "1.25rem", fontWeight: "600" }}>Community Impact</h3>
            </div>
            
            <ul style={{ margin: 0, paddingLeft: "1.5rem" }}>
              <li style={{ color: "#4b5563", marginBottom: "0.5rem" }}>
                Support local hospitals and patients
              </li>
              <li style={{ color: "#4b5563", marginBottom: "0.5rem" }}>
                Help during emergencies and natural disasters
              </li>
              <li style={{ color: "#4b5563", marginBottom: "0.5rem" }}>
                Contribute to medical research
              </li>
              <li style={{ color: "#4b5563" }}>
                Join a community of lifesavers
              </li>
            </ul>
          </div>
        </div>
      </div>
      
      {/* Testimonials */}
      <div style={{ backgroundColor: "#fef2f2", padding: "4rem 1.5rem", width: "100%" }}>
        <div style={{ maxWidth: "64rem", margin: "0 auto" }}>
          <h2 style={{ 
            fontSize: "1.875rem", 
            fontWeight: "700", 
            textAlign: "center", 
            marginBottom: "3rem", 
            color: "#1f2937" 
          }}>Donor Stories</h2>
          
          <div style={{ 
            display: "grid", 
            gridTemplateColumns: "repeat(auto-fit, minmax(300px, 1fr))", 
            gap: "2rem" 
          }}>
            <div style={{ 
              backgroundColor: "white", 
              padding: "1.5rem", 
              borderRadius: "0.5rem", 
              boxShadow: "0 4px 6px rgba(0,0,0,0.1)" 
            }}>
              <div style={{ 
                color: "#dc2626", 
                fontSize: "2.5rem", 
                fontFamily: "serif", 
                marginBottom: "1rem" 
              }}>"</div>
              <p style={{ 
                color: "#4b5563", 
                fontStyle: "italic", 
                marginBottom: "1rem" 
              }}>
                Donating blood regularly has become a meaningful part of my life. Knowing I'm helping others gives me purpose.
              </p>
              <p style={{ 
                color: "#1f2937", 
                fontWeight: "500" 
              }}>— Sarah J., Donor since 2018</p>
            </div>
            
            <div style={{ 
              backgroundColor: "white", 
              padding: "1.5rem", 
              borderRadius: "0.5rem", 
              boxShadow: "0 4px 6px rgba(0,0,0,0.1)" 
            }}>
              <div style={{ 
                color: "#dc2626", 
                fontSize: "2.5rem", 
                fontFamily: "serif", 
                marginBottom: "1rem" 
              }}>"</div>
              <p style={{ 
                color: "#4b5563", 
                fontStyle: "italic", 
                marginBottom: "1rem" 
              }}>
                After receiving blood during my surgery, I became a donor. It's the least I can do to pay it forward.
              </p>
              <p style={{ 
                color: "#1f2937", 
                fontWeight: "500" 
              }}>— Michael T., Recipient & Donor</p>
            </div>
          </div>
        </div>
      </div>
      
      {/* Call to Action */}
      <div style={{ 
        background: "linear-gradient(to right, #dc2626, #991b1b)", 
        color: "white", 
        padding: "4rem 1.5rem",
        width: "100%" 
      }}>
        <div style={{ maxWidth: "64rem", margin: "0 auto", textAlign: "center" }}>
          <h2 style={{ 
            fontSize: "1.875rem", 
            fontWeight: "700", 
            marginBottom: "1.5rem" 
          }}>Ready to Make a Difference?</h2>
          <p style={{ 
            fontSize: "1.25rem", 
            maxWidth: "600px", 
            margin: "0 auto 2rem auto" 
          }}>
            Join our community of donors today and become part of something bigger than yourself.
          </p>
          <div style={{ display: "flex", flexDirection: "column", gap: "1rem", alignItems: "center" }}>
            <button 
              style={{ 
                backgroundColor: "white", 
                color: "#dc2626", 
                fontWeight: "bold", 
                padding: "0.75rem 2rem", 
                borderRadius: "9999px", 
                border: "none", 
                cursor: "pointer", 
                fontSize: "1rem",
                transition: "transform 0.3s ease",
              }}
              onMouseOver={(e) => e.currentTarget.style.transform = "scale(1.1)"}
              onMouseOut={(e) => e.currentTarget.style.transform = "scale(1)"}
              onClick={() => navigate("/donor")}
            >
              Schedule a Donation
            </button>
            <button 
              style={{ 
                backgroundColor: "transparent", 
                color: "white", 
                fontWeight: "bold", 
                padding: "0.75rem 2rem", 
                borderRadius: "9999px", 
                border: "2px solid white", 
                cursor: "pointer", 
                fontSize: "1rem",
                transition: "transform 0.3s ease",
              }}
              onMouseOver={(e) => e.currentTarget.style.transform = "scale(1.1)"}
              onMouseOut={(e) => e.currentTarget.style.transform = "scale(1)"}
              onClick={() => navigate("/learn-more")}
            >
              Learn More
            </button>
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

export default About;