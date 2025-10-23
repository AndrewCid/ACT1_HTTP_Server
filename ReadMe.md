# ACT1_HTTP_Server: Simple PHP Socket HTTP Server
This project implements a basic HTTP server using PHP's socket functions, capable of
serving 200 (Success) and 404 (Not Found) responses.

## Group Partners:
- **Lead Developer:** Aaron Andrew L. Cid  
- **Collaborator:** Celein M. Laniohan

---

## V1.1 Patchnotes

added a touch of beauty and functionality to the webpages

---

## NEW FEATURES

### Core Server Functions
- **TCP/IP Socket Server** - Built entirely with PHP's native socket functions, no frameworks required
- **HTTP Protocol Support** - Handles standard HTTP/1.1 GET requests with proper status codes
- **Static File Serving** - Automatic MIME type detection and delivery for HTML, CSS, JS, and image files
- **Multi-Request Handling** - Persistent server loop accepts unlimited client connections
- **Smart Routing** - Intelligent path resolution with automatic index.html fallback

### Response System
- **200 OK Handler** - Beautiful success page with glassmorphic design
- **404 Not Found Handler** - Stylish error page showing requested path
- **Chunked Transfer** - Efficient 8KB chunk transmission prevents connection resets
- **Graceful Shutdown** - Proper socket cleanup ensures stable client disconnections

### Modern UI Design
- **Glassmorphism Effects** - Frosted glass cards with backdrop blur
- **Background Support** - Full-screen image/GIF backgrounds with fallback colors
- **Responsive Layout** - Mobile-friendly design that scales to any screen size
- **Interactive Navigation** - Smooth hover transitions and button animations
- **Shadow Depth** - Professional drop shadows for visual hierarchy

---

## TECHNICAL SPECIFICATIONS

**Server Configuration:**
- Host: `127.0.0.1` (localhost)
- Port: `8080`
- Protocol: `HTTP/1.1`
- Socket Type: `TCP/IP (SOCK_STREAM)`

**Supported File Types:**
- HTML, CSS, JavaScript
- Images: JPG, JPEG, PNG, GIF

---