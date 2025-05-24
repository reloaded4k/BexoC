from main import app

# This is used by the WSGI server to serve the application
application = app

if __name__ == "__main__":
    app.run()