import time
import random
from datetime import datetime, timedelta

# Categories mapping
CATEGORIES = {
    'girl': [1, 2, 6, 7, 8],
    'boy': [3, 4, 5]
}

def generate_sample_products(num_products=100):
    """Generate sample products for testing."""
    products = []
    now = datetime.now()
    for i in range(num_products):
        code = f'P{i:03d}'
        category_id = random.choice(list(range(1, 9)))
        featured = random.choice([0, 1])
        discount = random.uniform(0, 50)
        created_at = now - timedelta(days=random.randint(1, 365))
        price = random.uniform(10000, 500000)
        product = {
            'code': code,
            'category_id': category_id,
            'featured': featured,
            'discount': discount,
            'created_at': created_at,
            'price': price,
            'variants': generate_sample_variants()
        }
        products.append(product)
    return products

def generate_sample_variants(num_variants=5):
    """Generate sample variants for a product."""
    sizes = [f'{i}Y' for i in range(1, 13)]
    colors = ['red', 'blue', 'green', 'yellow', 'pink']
    variants = []
    for _ in range(num_variants):
        variants.append({
            'size': random.choice(sizes),
            'color': random.choice(colors),
            'stock': random.randint(0, 100)
        })
    return variants

def recommend(age: int, gender: str, products: list) -> list:
    """
    AI Recommendation Module: Replicates logic from recommend.php.
    - Filters products by gender categories.
    - Computes AI score based on size, featured, discount, recency, stock.
    - Returns top 24 products sorted by AI score.
    """
    # Validate gender input
    gender = gender.lower()
    if gender not in CATEGORIES:
        raise ValueError("Invalid gender. Must be 'boy' or 'girl'.")

    # Get category IDs for the specified gender
    cats = CATEGORIES[gender]
    
    # Ensure age is within valid range (0-12)
    age = max(1, min(12, age))
    target_size = f'{age}Y'
    near_sizes = [f'{max(1, age-1)}Y', target_size, f'{min(12, age+1)}Y']
    
    now = time.time()
    out = []
    for p in products:
        # Filter by gender-specific categories
        if p['category_id'] not in cats:
            continue
        
        size_score = 0
        stock_score = 0
        for v in p['variants']:
            # Check if variant size matches target or nearby sizes
            if v['size'] in near_sizes:
                size_score = max(size_score, 1.0 if v['size'] == target_size else 0.6)
            stock_score = max(stock_score, min(1.0, v['stock'] / 60.0))
        
        # Calculate scores for featured, discount, and recency
        featured_score = 0.6 if p['featured'] == 1 else 0.0
        discount_score = min(0.8, p['discount'] / 20.0)
        recency_days = max(1, (now - p['created_at'].timestamp()) / 86400)
        recency_score = min(0.8, 14 / recency_days)
        
        # Compute AI score
        ai_score = (0.45 * size_score + 0.2 * featured_score + 0.15 * discount_score + 
                    0.2 * recency_score + 0.2 * stock_score)
        
        p['ai_score'] = round(ai_score, 4)
        out.append(p)
    
    # Sort by AI score and return top 24
    out.sort(key=lambda x: x['ai_score'], reverse=True)
    return out[:24]

# Example usage and testing
if __name__ == "__main__":
    # Generate sample products
    products = generate_sample_products(100)
    
    # Test recommendations for both boy and girl
    genders = ['boy', 'girl']
    age = 5
    
    for gender in genders:
        print(f"\nRecommendations for {gender.capitalize()} (Age {age}):")
        try:
            recommendations = recommend(age, gender, products)
            if not recommendations:
                print(f"No products found for {gender}.")
            else:
                print(f"Top 5 recommendations for {gender.capitalize()}:")
                for p in recommendations[:5]:
                    print(f"Code: {p['code']}, AI Score: {p['ai_score']}, Category: {p['category_id']}, "
                          f"Price: {p['price']:,.0f} VND, Discount: {p['discount']:.1f}%, "
                          f"Variants: {[f'{v['size']} ({v['color']}, stock: {v['stock']})' for v in p['variants']]}")
        except ValueError as e:
            print(f"Error: {e}")