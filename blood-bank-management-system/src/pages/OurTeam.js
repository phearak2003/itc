import React, { useState } from "react";

const OurTeam = () => {
  const [activeIndex, setActiveIndex] = useState(null);

  const handleCardClick = (index) => {
    setActiveIndex(activeIndex === index ? null : index);
  };

  // Simple Header component matching the Home page style
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
        }} onClick={() => window.location.href = "/"}>
          <span style={{ color: "#dc2626", fontSize: "24px" }}>❤</span>
          <span style={{ marginLeft: "8px", fontWeight: "bold", fontSize: "18px" }}>BloodDonate</span>
        </div>
        <nav>
          <a href="/" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Home</a>
          <a href="/about" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>About</a>
          <a href="/ourteam" style={{ 
            margin: "0 10px", 
            color: "#dc2626", 
            fontWeight: "500", 
            textDecoration: "none",
            borderBottom: "2px solid #dc2626",
            paddingBottom: "5px"
          }}>Our Team</a>
          <a href="/contact" style={{ margin: "0 10px", color: "#374151", textDecoration: "none" }}>Contact</a>
        </nav>
      </div>
    </header>
  );

  const teamMembers = [
    {
      name: "CHHON Menghout",
      role: "Project Manager",
      image: "/assets/team/Menghout.jpg",
      bio: "Leads our team with vision and strategic planning to ensure every blood donation reaches those in need.",
      skills: ["Leadership", "Strategic Planning", "Healthcare Management"],
      linkedin: "#",
      instagram: "#",
      facebook: "#"
    },
    {
      name: "BO Sane",
      role: "Frontend Developer",
      image: "/assets/team/Sane.jpg",
      bio: "Creates intuitive interfaces that make blood donation registration and tracking simple for everyone.",
      skills: ["React", "UI/UX Design", "Mobile Development"],
      linkedin: "#",
      instagram: "#",
      facebook: "#"
    },
    {
      name: "Chantrea",
      role: "Backend Developer",
      image: "/assets/team/Chantria.jpg",
      bio: "Builds the robust systems that connect donors with hospitals and ensure data security and privacy.",
      skills: ["Database Management", "API Development", "Security"],
      linkedin: "#",
      instagram: "#",
      facebook: "#"
    },
    {
      name: "CHHORN Solita",
      role: "Health Coordinator",
      image: "/assets/team/Solita.jpg",
      bio: "Ensures medical standards and protocols are met throughout the donation process for donor and recipient safety.",
      skills: ["Healthcare Protocols", "Documentation", "Training"],
      linkedin: "#",
      instagram: "#",
      facebook: "#"
    },
    {
      name: "En Sreytoch",
      role: "Community Outreach",
      image: "/assets/team/Sreytoch.jpg",
      bio: "Builds relationships with communities and organizations to increase awareness about blood donation.",
      skills: ["Public Speaking", "Event Organization", "Community Building"],
      linkedin: "#",
      instagram: "#",
      facebook: "#"
    },
  ];

  return (
    <div style={{
      display: "flex",
      flexDirection: "column",
      minHeight: "100vh",
      backgroundColor: "#f3f4f6"
    }}>
      <Header />
      
      <section style={{
        padding: "3rem 1.5rem",
        maxWidth: "1200px",
        margin: "0 auto",
        width: "100%"
      }}>
        <div style={{
          textAlign: "center",
          marginBottom: "3rem"
        }}>
          <h1 style={{
            fontSize: "2.5rem",
            fontWeight: "700",
            color: "#dc2626",
            marginBottom: "1rem"
          }}>Meet Our Dedicated Team</h1>
          <p style={{
            fontSize: "1.125rem",
            color: "#4b5563",
            maxWidth: "800px",
            margin: "0 auto"
          }}>
            The passionate individuals behind BloodDonate who work tirelessly to connect donors 
            with those in need and make a difference in healthcare.
          </p>
        </div>

        <div style={{
          display: "grid",
          gridTemplateColumns: "repeat(auto-fill, minmax(300px, 1fr))",
          gap: "2rem"
        }}>
          {teamMembers.map((member, index) => (
            <div 
              key={index}
              style={{
                backgroundColor: "white",
                borderRadius: "12px",
                overflow: "hidden",
                boxShadow: "0 4px 6px rgba(0,0,0,0.1)",
                transition: "transform 0.3s ease, box-shadow 0.3s ease",
                cursor: "pointer"
              }}
              onMouseOver={(e) => {
                e.currentTarget.style.transform = "translateY(-5px)";
                e.currentTarget.style.boxShadow = "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)";
              }}
              onMouseOut={(e) => {
                e.currentTarget.style.transform = "translateY(0)";
                e.currentTarget.style.boxShadow = "0 4px 6px rgba(0,0,0,0.1)";
              }}
              onClick={() => handleCardClick(index)}
            >
              <div style={{
                position: "relative",
                height: "280px",
                overflow: "hidden"
              }}>
                <img 
                  src={member.image} 
                  alt={member.name} 
                  style={{
                    width: "100%",
                    height: "100%",
                    objectFit: "cover",
                    objectPosition: "center",
                    transition: "transform 0.5s ease"
                  }}
                  onMouseOver={(e) => e.currentTarget.style.transform = "scale(1.05)"}
                  onMouseOut={(e) => e.currentTarget.style.transform = "scale(1)"}
                />
                <div style={{
                  position: "absolute",
                  bottom: 0,
                  left: 0,
                  right: 0,
                  background: "linear-gradient(to top, rgba(0,0,0,0.7), transparent)",
                  padding: "1.5rem 1rem",
                  color: "white"
                }}>
                  <h3 style={{
                    fontSize: "1.25rem",
                    fontWeight: "600",
                    marginBottom: "0.25rem"
                  }}>{member.name}</h3>
                  <p style={{
                    fontSize: "1rem",
                    color: "rgba(255,255,255,0.9)"
                  }}>{member.role}</p>
                </div>
              </div>
              
              <div style={{
                padding: "1.5rem",
                transition: "max-height 0.3s ease",
                overflow: "hidden",
                maxHeight: activeIndex === index ? "500px" : "100px"
              }}>
                <h3 style={{
                  fontSize: "1.25rem",
                  fontWeight: "600",
                  color: "#111827",
                  marginBottom: "0.5rem"
                }}>{member.name}</h3>
                <p style={{
                  fontSize: "1rem",
                  color: "#dc2626",
                  fontWeight: "500",
                  marginBottom: "1rem"
                }}>{member.role}</p>
                
                {/* Content that appears when card is clicked */}
                <div style={{
                  opacity: activeIndex === index ? 1 : 0,
                  height: activeIndex === index ? "auto" : 0,
                  transition: "opacity 0.3s ease, height 0.3s ease",
                  overflow: "hidden"
                }}>
                  <p style={{
                    fontSize: "0.95rem",
                    color: "#4b5563",
                    marginBottom: "1rem",
                    lineHeight: "1.5"
                  }}>{member.bio}</p>
                  
                  <div style={{ marginBottom: "1rem" }}>
                    <h4 style={{
                      fontSize: "0.875rem",
                      fontWeight: "600",
                      color: "#374151",
                      marginBottom: "0.5rem"
                    }}>Key Skills:</h4>
                    <div style={{
                      display: "flex",
                      flexWrap: "wrap",
                      gap: "0.5rem"
                    }}>
                      {member.skills.map((skill, i) => (
                        <span 
                          key={i}
                          style={{
                            backgroundColor: "#fee2e2",
                            color: "#dc2626",
                            fontSize: "0.75rem",
                            padding: "0.25rem 0.75rem",
                            borderRadius: "9999px"
                          }}
                        >
                          {skill}
                        </span>
                      ))}
                    </div>
                  </div>
                </div>
                
                <div style={{
                  display: "flex",
                  gap: "1rem",
                  marginTop: "1rem"
                }}>
                  <a 
                    href={member.linkedin} 
                    target="_blank" 
                    rel="noopener noreferrer"
                    style={{
                      color: "#4b5563",
                      transition: "color 0.2s ease"
                    }}
                    onMouseOver={(e) => e.currentTarget.style.color = "#0077b5"}
                    onMouseOut={(e) => e.currentTarget.style.color = "#4b5563"}
                  >
                    <svg fill="currentColor" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                  </a>
                  <a 
                    href={member.instagram} 
                    target="_blank" 
                    rel="noopener noreferrer"
                    style={{
                      color: "#4b5563",
                      transition: "color 0.2s ease"
                    }}
                    onMouseOver={(e) => e.currentTarget.style.color = "#e4405f"}
                    onMouseOut={(e) => e.currentTarget.style.color = "#4b5563"}
                  >
                    <svg fill="currentColor" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                  </a>
                  <a 
                    href={member.facebook} 
                    target="_blank" 
                    rel="noopener noreferrer"
                    style={{
                      color: "#4b5563",
                      transition: "color 0.2s ease"
                    }}
                    onMouseOver={(e) => e.currentTarget.style.color = "#1877f2"}
                    onMouseOut={(e) => e.currentTarget.style.color = "#4b5563"}
                  >
                    <svg fill="currentColor" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M22 12c0-5.523-4.477-10-10-10s-10 4.477-10 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54v-2.891h2.54v-2.203c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.891h-2.33v6.988c4.781-.75 8.437-4.887 8.437-9.878z"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Join the Team Section */}
        <div style={{
          marginTop: "4rem",
          textAlign: "center",
          backgroundColor: "#fee2e2",
          borderRadius: "12px",
          padding: "2.5rem 2rem",
          boxShadow: "0 4px 6px rgba(0,0,0,0.05)"
        }}>
          <h2 style={{
            fontSize: "1.875rem",
            fontWeight: "700",
            color: "#dc2626",
            marginBottom: "1rem"
          }}>Join Our Life-Saving Mission</h2>
          <p style={{
            fontSize: "1.125rem",
            color: "#4b5563",
            maxWidth: "700px",
            margin: "0 auto 1.5rem"
          }}>
            We're always looking for passionate individuals to help us expand our impact and save more lives.
          </p>
          <button 
            style={{
              backgroundColor: "#dc2626",
              color: "white",
              fontWeight: "600",
              padding: "0.75rem 2rem",
              borderRadius: "9999px",
              border: "none",
              boxShadow: "0 4px 6px rgba(220, 38, 38, 0.3)",
              cursor: "pointer",
              transition: "transform 0.3s ease, background-color 0.3s ease",
              fontSize: "1rem"
            }}
            onMouseOver={(e) => {
              e.currentTarget.style.transform = "translateY(-3px)";
              e.currentTarget.style.backgroundColor = "#b91c1c";
            }}
            onMouseOut={(e) => {
              e.currentTarget.style.transform = "translateY(0)";
              e.currentTarget.style.backgroundColor = "#dc2626";
            }}
            onClick={() => window.location.href = "/careers"}
          >
            View Open Positions
          </button>
        </div>
      </section>

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
            marginBottom: "1.5rem",
            cursor: "pointer"
          }} onClick={() => window.location.href = "/"}>
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

export default OurTeam;