import random
import string

def generate_tracking_number(prefix="BX", length=10):
    """
    Generate a unique tracking number
    
    Args:
        prefix (str): Prefix for the tracking number
        length (int): Length of the random part of the tracking number
    
    Returns:
        str: A unique tracking number
    """
    # Generate random alphanumeric string
    random_part = ''.join(random.choices(string.ascii_uppercase + string.digits, k=length))
    
    # Combine prefix and random part
    tracking_number = f"{prefix}{random_part}"
    
    return tracking_number
