"""
Website Builder Configuration
Customize your website builder settings here
"""

# Server Configuration
BACKEND_HOST = '0.0.0.0'
BACKEND_PORT = 5000
FRONTEND_PORT = 8000

# API Configuration
API_BASE_URL = f'http://localhost:{BACKEND_PORT}/api'

# Template Configuration
DEFAULT_TEMPLATE = 'business'

# Export Configuration
EXPORT_DIRECTORY = 'exports'
EXPORT_FORMAT = 'html'

# Component Styles
DEFAULT_COMPONENT_STYLES = {
    'header': {
        'backgroundColor': '#333',
        'color': 'white',
        'padding': '20px',
        'textAlign': 'center'
    },
    'hero': {
        'backgroundColor': '#f4f4f4',
        'padding': '40px',
        'textAlign': 'center'
    },
    'section': {
        'padding': '40px'
    },
    'footer': {
        'backgroundColor': '#333',
        'color': 'white',
        'padding': '20px',
        'textAlign': 'center'
    }
}

# Available Templates
TEMPLATES = {
    'business': {
        'name': 'Business Template',
        'description': 'Professional business website template',
        'components': [
            {
                'id': 'header',
                'type': 'header',
                'content': 'Welcome to Our Business',
                'style': DEFAULT_COMPONENT_STYLES['header']
            },
            {
                'id': 'hero',
                'type': 'hero',
                'content': 'Professional Business Solutions',
                'style': DEFAULT_COMPONENT_STYLES['hero']
            },
            {
                'id': 'services',
                'type': 'section',
                'content': 'Our Services',
                'style': DEFAULT_COMPONENT_STYLES['section']
            },
            {
                'id': 'footer',
                'type': 'footer',
                'content': '© 2024 Business Name. All rights reserved.',
                'style': DEFAULT_COMPONENT_STYLES['footer']
            }
        ]
    },
    'portfolio': {
        'name': 'Portfolio Template',
        'description': 'Creative portfolio website template',
        'components': [
            {
                'id': 'header',
                'type': 'header',
                'content': 'John Doe - Portfolio',
                'style': {'backgroundColor': '#2c3e50', 'color': 'white', 'padding': '20px'}
            },
            {
                'id': 'hero',
                'type': 'hero',
                'content': 'Creative Developer & Designer',
                'style': {'backgroundColor': '#3498db', 'color': 'white', 'padding': '60px', 'textAlign': 'center'}
            },
            {
                'id': 'projects',
                'type': 'section',
                'content': 'Featured Projects',
                'style': {'padding': '40px', 'backgroundColor': '#ecf0f1'}
            },
            {
                'id': 'contact',
                'type': 'section',
                'content': 'Get In Touch',
                'style': {'padding': '40px'}
            },
            {
                'id': 'footer',
                'type': 'footer',
                'content': '© 2024 John Doe. All rights reserved.',
                'style': {'backgroundColor': '#2c3e50', 'color': 'white', 'padding': '20px', 'textAlign': 'center'}
            }
        ]
    },
    'blog': {
        'name': 'Blog Template',
        'description': 'Modern blog website template',
        'components': [
            {
                'id': 'header',
                'type': 'header',
                'content': 'My Blog',
                'style': {'backgroundColor': '#e74c3c', 'color': 'white', 'padding': '20px'}
            },
            {
                'id': 'hero',
                'type': 'hero',
                'content': 'Latest Posts',
                'style': {'backgroundColor': '#f8f9fa', 'padding': '40px', 'textAlign': 'center'}
            },
            {
                'id': 'posts',
                'type': 'section',
                'content': 'Blog Posts',
                'style': {'padding': '40px'}
            },
            {
                'id': 'sidebar',
                'type': 'sidebar',
                'content': 'Categories',
                'style': {'backgroundColor': '#f1f2f6', 'padding': '20px'}
            },
            {
                'id': 'footer',
                'type': 'footer',
                'content': '© 2024 My Blog. All rights reserved.',
                'style': {'backgroundColor': '#e74c3c', 'color': 'white', 'padding': '20px', 'textAlign': 'center'}
            }
        ]
    }
}

# Available Components
COMPONENTS = [
    {'type': 'header', 'name': 'Header', 'icon': '📋', 'description': 'Website header with navigation'},
    {'type': 'hero', 'name': 'Hero Section', 'icon': '🎯', 'description': 'Eye-catching banner area'},
    {'type': 'section', 'name': 'Content Section', 'icon': '📄', 'description': 'General content area'},
    {'type': 'sidebar', 'name': 'Sidebar', 'icon': '📌', 'description': 'Side navigation or information'},
    {'type': 'footer', 'name': 'Footer', 'icon': '🦶', 'description': 'Website footer'},
    {'type': 'text', 'name': 'Text Block', 'icon': '📝', 'description': 'Rich text content'},
    {'type': 'image', 'name': 'Image', 'icon': '🖼️', 'description': 'Image placeholder'},
    {'type': 'button', 'name': 'Button', 'icon': '🔘', 'description': 'Call-to-action buttons'}
]

# CSS Framework Configuration
CSS_FRAMEWORK = {
    'bootstrap': {
        'enabled': False,
        'version': '5.3.0',
        'cdn': 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
    },
    'tailwind': {
        'enabled': False,
        'version': '3.3.0',
        'cdn': 'https://cdn.tailwindcss.com'
    }
}

# Export Settings
EXPORT_SETTINGS = {
    'include_css': True,
    'include_js': True,
    'minify_output': False,
    'add_meta_tags': True,
    'responsive_design': True
}
