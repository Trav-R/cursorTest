<?php
/**
 * Website Builder - PHP Frontend Interface
 * A modern drag-and-drop website builder interface
 */

// Configuration
$api_url = 'http://localhost:5000/api';
$current_project = null;

// Handle project operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_project':
                $project_data = [
                    'name' => $_POST['project_name'] ?? 'Untitled Project',
                    'template' => $_POST['template'] ?? 'business'
                ];
                $response = make_api_request('POST', '/projects', $project_data);
                if ($response) {
                    $current_project = $response;
                }
                break;
            case 'update_project':
                $project_id = $_POST['project_id'];
                $components = json_decode($_POST['components'], true);
                $response = make_api_request('PUT', "/projects/$project_id", [
                    'components' => $components
                ]);
                if ($response) {
                    $current_project = $response;
                }
                break;
        }
    }
}

// Get project if ID is provided
if (isset($_GET['project_id'])) {
    $response = make_api_request('GET', "/projects/{$_GET['project_id']}");
    if ($response) {
        $current_project = $response;
    }
}

function make_api_request($method, $endpoint, $data = null) {
    global $api_url;
    
    $url = $api_url . $endpoint;
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data && in_array($method, ['POST', 'PUT'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200) {
        return json_decode($response, true);
    }
    
    return null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Builder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            height: 100vh;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #1e7e34;
        }

        .main-container {
            display: flex;
            height: calc(100vh - 80px);
        }

        .sidebar {
            width: 300px;
            background: white;
            border-right: 1px solid #e0e0e0;
            padding: 1rem;
            overflow-y: auto;
        }

        .sidebar h3 {
            margin-bottom: 1rem;
            color: #333;
            font-size: 1.1rem;
        }

        .component-list {
            display: grid;
            gap: 0.5rem;
        }

        .component-item {
            padding: 0.75rem;
            border: 2px dashed #ddd;
            border-radius: 8px;
            cursor: grab;
            transition: all 0.3s ease;
            text-align: center;
            background: #fafafa;
        }

        .component-item:hover {
            border-color: #007bff;
            background: #f0f8ff;
            transform: translateY(-2px);
        }

        .component-item:active {
            cursor: grabbing;
        }

        .component-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .canvas-container {
            flex: 1;
            background: #f8f9fa;
            position: relative;
            overflow: auto;
        }

        .canvas {
            min-height: 100%;
            background: white;
            margin: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: relative;
        }

        .canvas-item {
            border: 2px solid transparent;
            padding: 1rem;
            margin: 0.5rem;
            border-radius: 5px;
            cursor: move;
            transition: all 0.3s ease;
            position: relative;
        }

        .canvas-item:hover {
            border-color: #007bff;
            box-shadow: 0 2px 10px rgba(0,123,255,0.2);
        }

        .canvas-item.selected {
            border-color: #28a745;
            box-shadow: 0 0 0 2px rgba(40,167,69,0.3);
        }

        .canvas-item .delete-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            font-size: 12px;
            display: none;
        }

        .canvas-item:hover .delete-btn {
            display: block;
        }

        .template-selector {
            margin-bottom: 2rem;
        }

        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .template-card {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .template-card:hover {
            border-color: #007bff;
            transform: translateY(-2px);
        }

        .template-card.selected {
            border-color: #28a745;
            background: #f8fff9;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .empty-canvas {
            text-align: center;
            padding: 4rem;
            color: #666;
        }

        .empty-canvas h3 {
            margin-bottom: 1rem;
        }

        .empty-canvas p {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚀 Website Builder</h1>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openNewProjectModal()">New Project</button>
            <button class="btn btn-success" onclick="exportProject()" <?php echo $current_project ? '' : 'disabled'; ?>>Export</button>
        </div>
    </div>

    <div class="main-container">
        <div class="sidebar">
            <div class="template-selector">
                <h3>📋 Templates</h3>
                <div class="template-grid" id="templateGrid">
                    <!-- Templates will be loaded here -->
                </div>
            </div>

            <div>
                <h3>🧩 Components</h3>
                <div class="component-list" id="componentList">
                    <!-- Components will be loaded here -->
                </div>
            </div>
        </div>

        <div class="canvas-container">
            <div class="canvas" id="canvas">
                <?php if ($current_project): ?>
                    <div class="loading" id="loading">
                        <div class="spinner"></div>
                        <p>Loading project...</p>
                    </div>
                <?php else: ?>
                    <div class="empty-canvas">
                        <h3>Welcome to Website Builder!</h3>
                        <p>Start by creating a new project or selecting a template.</p>
                        <button class="btn btn-primary" onclick="openNewProjectModal()">Create New Project</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- New Project Modal -->
    <div id="newProjectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Create New Project</h2>
                <span class="close" onclick="closeNewProjectModal()">&times;</span>
            </div>
            <form method="POST" id="newProjectForm">
                <input type="hidden" name="action" value="create_project">
                <div class="form-group">
                    <label for="project_name">Project Name:</label>
                    <input type="text" id="project_name" name="project_name" value="My Website" required>
                </div>
                <div class="form-group">
                    <label for="template">Template:</label>
                    <select id="template" name="template">
                        <option value="business">Business</option>
                        <option value="portfolio">Portfolio</option>
                        <option value="blog">Blog</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Project</button>
            </form>
        </div>
    </div>

    <script>
        // Global variables
        let currentProject = <?php echo $current_project ? json_encode($current_project) : 'null'; ?>;
        let selectedTemplate = null;
        let draggedComponent = null;

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            loadTemplates();
            loadComponents();
            
            if (currentProject) {
                loadProject(currentProject);
            }
        });

        // Load templates
        async function loadTemplates() {
            try {
                const response = await fetch('http://localhost:5000/api/templates');
                const templates = await response.json();
                
                const templateGrid = document.getElementById('templateGrid');
                templateGrid.innerHTML = '';
                
                Object.entries(templates).forEach(([key, template]) => {
                    const templateCard = document.createElement('div');
                    templateCard.className = 'template-card';
                    templateCard.innerHTML = `
                        <h4>${template.name}</h4>
                        <p>${template.components.length} components</p>
                    `;
                    templateCard.onclick = () => selectTemplate(key, templateCard);
                    templateGrid.appendChild(templateCard);
                });
            } catch (error) {
                console.error('Error loading templates:', error);
            }
        }

        // Load components
        async function loadComponents() {
            try {
                const response = await fetch('http://localhost:5000/api/components');
                const components = await response.json();
                
                const componentList = document.getElementById('componentList');
                componentList.innerHTML = '';
                
                components.forEach(component => {
                    const componentItem = document.createElement('div');
                    componentItem.className = 'component-item';
                    componentItem.draggable = true;
                    componentItem.innerHTML = `
                        <div class="component-icon">${component.icon}</div>
                        <div>${component.name}</div>
                    `;
                    componentItem.ondragstart = (e) => {
                        draggedComponent = component;
                        e.dataTransfer.effectAllowed = 'copy';
                    };
                    componentList.appendChild(componentItem);
                });
            } catch (error) {
                console.error('Error loading components:', error);
            }
        }

        // Select template
        function selectTemplate(templateKey, element) {
            // Remove previous selection
            document.querySelectorAll('.template-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection to clicked template
            element.classList.add('selected');
            selectedTemplate = templateKey;
        }

        // Open new project modal
        function openNewProjectModal() {
            document.getElementById('newProjectModal').style.display = 'block';
        }

        // Close new project modal
        function closeNewProjectModal() {
            document.getElementById('newProjectModal').style.display = 'none';
        }

        // Load project into canvas
        function loadProject(project) {
            const canvas = document.getElementById('canvas');
            canvas.innerHTML = '';
            
            if (project.components) {
                project.components.forEach((component, index) => {
                    addComponentToCanvas(component, index);
                });
            }
        }

        // Add component to canvas
        function addComponentToCanvas(component, index) {
            const canvas = document.getElementById('canvas');
            const componentDiv = document.createElement('div');
            componentDiv.className = 'canvas-item';
            componentDiv.dataset.index = index;
            componentDiv.innerHTML = `
                <div style="${Object.entries(component.style || {}).map(([k, v]) => `${k}: ${v}`).join('; ')}">
                    ${component.content}
                </div>
                <button class="delete-btn" onclick="removeComponent(${index})">×</button>
            `;
            
            componentDiv.onclick = (e) => {
                e.stopPropagation();
                selectComponent(componentDiv);
            };
            
            canvas.appendChild(componentDiv);
        }

        // Select component
        function selectComponent(element) {
            document.querySelectorAll('.canvas-item').forEach(item => {
                item.classList.remove('selected');
            });
            element.classList.add('selected');
        }

        // Remove component
        function removeComponent(index) {
            if (currentProject && currentProject.components) {
                currentProject.components.splice(index, 1);
                loadProject(currentProject);
                saveProject();
            }
        }

        // Save project
        async function saveProject() {
            if (!currentProject) return;
            
            try {
                const response = await fetch(`http://localhost:5000/api/projects/${currentProject.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(currentProject)
                });
                
                if (response.ok) {
                    console.log('Project saved successfully');
                }
            } catch (error) {
                console.error('Error saving project:', error);
            }
        }

        // Export project
        async function exportProject() {
            if (!currentProject) return;
            
            try {
                const response = await fetch(`http://localhost:5000/api/projects/${currentProject.id}/export`, {
                    method: 'POST'
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    // Create download link
                    const blob = new Blob([result.content], { type: 'text/html' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = result.filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                    
                    alert('Project exported successfully!');
                } else {
                    alert('Error exporting project: ' + result.error);
                }
            } catch (error) {
                console.error('Error exporting project:', error);
                alert('Error exporting project');
            }
        }

        // Canvas drop functionality
        document.getElementById('canvas').ondragover = (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'copy';
        };

        document.getElementById('canvas').ondrop = (e) => {
            e.preventDefault();
            
            if (draggedComponent && currentProject) {
                const newComponent = {
                    id: 'comp_' + Date.now(),
                    type: draggedComponent.type,
                    content: `New ${draggedComponent.name}`,
                    style: {
                        padding: '20px',
                        backgroundColor: '#f8f9fa',
                        border: '1px solid #dee2e6',
                        borderRadius: '5px'
                    }
                };
                
                currentProject.components.push(newComponent);
                loadProject(currentProject);
                saveProject();
            }
        };

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('newProjectModal');
            if (event.target === modal) {
                closeNewProjectModal();
            }
        };
    </script>
</body>
</html>
