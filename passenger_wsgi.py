import sys
import os

# Add application directory to path
INTERP = os.path.expanduser("/home/USERNAME/public_html/bexocargo/venv/bin/python3")
if sys.executable != INTERP:
    os.execl(INTERP, INTERP, *sys.argv)

# Add the application path to system path
sys.path.insert(0, os.path.dirname(__file__))

# Import app from main.py
from main import app
application = app