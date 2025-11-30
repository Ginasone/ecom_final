/**
 * Pickup Points Management JavaScript
 * Handles admin CRUD operations for pickup points
 */

$(document).ready(function() {
    
    // Load pickup points when page loads
    loadPickupPoints();
    
    // Add pickup point form submit
    $('#add-pickup-point-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            action: 'add',
            name: $('#point_name').val().trim(),
            region: $('#region').val(),
            city: $('#city').val().trim(),
            address: $('#address').val().trim(),
            contact_person: $('#contact_person').val().trim(),
            contact_phone: $('#contact_phone').val().trim(),
            operating_hours: $('#operating_hours').val().trim(),
            is_active: $('#is_active').is(':checked') ? 1 : 0
        };
        
        // Validate
        if (!formData.name || !formData.region || !formData.city || !formData.address) {
            alert('Please fill all required fields');
            return;
        }
        
        // Disable button
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Adding...');
        
        $.ajax({
            url: '../actions/manage_pickup_point_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fa fa-plus me-2"></i>Add Pickup Point');
                
                if (response.status === 'success') {
                    alert(response.message);
                    $('#add-pickup-point-form')[0].reset();
                    loadPickupPoints();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fa fa-plus me-2"></i>Add Pickup Point');
                alert('Failed to add pickup point. Please try again.');
            }
        });
    });
    
    // Edit pickup point form submit
    $('#edit-pickup-point-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            action: 'update',
            point_id: $('#edit_point_id').val(),
            name: $('#edit_point_name').val().trim(),
            region: $('#edit_region').val(),
            city: $('#edit_city').val().trim(),
            address: $('#edit_address').val().trim(),
            contact_person: $('#edit_contact_person').val().trim(),
            contact_phone: $('#edit_contact_phone').val().trim(),
            operating_hours: $('#edit_operating_hours').val().trim(),
            is_active: $('#edit_is_active').is(':checked') ? 1 : 0
        };
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');
        
        $.ajax({
            url: '../actions/manage_pickup_point_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('Update Pickup Point');
                
                if (response.status === 'success') {
                    alert(response.message);
                    $('#editModal').modal('hide');
                    loadPickupPoints();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('Update Pickup Point');
                alert('Failed to update pickup point. Please try again.');
            }
        });
    });
    
    // Load pickup points function
    function loadPickupPoints(region = '') {
        var url = '../actions/get_pickup_points_action.php?action=get_all';
        
        if (region) {
            url = '../actions/get_pickup_points_action.php?action=get_by_region&region=' + encodeURIComponent(region);
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayPickupPoints(response.data);
                } else {
                    $('#pickup-points-list').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#pickup-points-list').html('<div class="alert alert-danger">Failed to load pickup points</div>');
            }
        });
    }
    
    // Display pickup points grouped by region
    function displayPickupPoints(points) {
        var html = '';
        
        if (points.length === 0) {
            html = '<div class="alert alert-info"><i class="fa fa-info-circle me-2"></i>No pickup points found. Add your first pickup point!</div>';
        } else {
            // Group by region
            var groupedPoints = {};
            for (var i = 0; i < points.length; i++) {
                var point = points[i];
                var region = point.region;
                
                if (!groupedPoints[region]) {
                    groupedPoints[region] = [];
                }
                groupedPoints[region].push(point);
            }
            
            // Display by region
            for (var region in groupedPoints) {
                html += '<div class="region-section">';
                html += '<div class="region-header"><strong><i class="fa fa-map-marker-alt me-2"></i>' + region + ' (' + groupedPoints[region].length + ' points)</strong></div>';
                
                var regionPoints = groupedPoints[region];
                for (var j = 0; j < regionPoints.length; j++) {
                    var point = regionPoints[j];
                    html += '<div class="pickup-point-card">';
                    html += '<div class="row align-items-center">';
                    html += '<div class="col-md-8">';
                    html += '<h6 class="mb-1">' + point.name + ' <span class="badge status-badge ' + (point.is_active == 1 ? 'bg-success' : 'bg-secondary') + '">' + (point.is_active == 1 ? 'Active' : 'Inactive') + '</span></h6>';
                    html += '<p class="mb-1 text-muted"><i class="fa fa-map-marker-alt me-1"></i>' + point.address + ', ' + point.city + '</p>';
                    if (point.contact_person) {
                        html += '<p class="mb-1 text-muted"><i class="fa fa-user me-1"></i>' + point.contact_person;
                        if (point.contact_phone) html += ' • <i class="fa fa-phone me-1"></i>' + point.contact_phone;
                        html += '</p>';
                    }
                    html += '<p class="mb-0 text-muted"><small><i class="fa fa-clock me-1"></i>' + point.operating_hours + '</small></p>';
                    html += '</div>';
                    html += '<div class="col-md-4 text-end">';
                    html += '<button class="btn btn-sm btn-warning me-1" onclick="editPickupPoint(' + point.point_id + ')">';
                    html += '<i class="fa fa-edit"></i> Edit</button>';
                    html += '<button class="btn btn-sm ' + (point.is_active == 1 ? 'btn-secondary' : 'btn-success') + ' me-1" onclick="toggleStatus(' + point.point_id + ')">';
                    html += '<i class="fa fa-' + (point.is_active == 1 ? 'pause' : 'play') + '"></i></button>';
                    html += '<button class="btn btn-sm btn-danger" onclick="deletePickupPoint(' + point.point_id + ', \'' + escapeHtml(point.name) + '\')">';
                    html += '<i class="fa fa-trash"></i></button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                }
                
                html += '</div>';
            }
        }
        
        $('#pickup-points-list').html(html);
    }
    
    // Filter pickup points by region
    window.filterPickupPoints = function() {
        var selectedRegion = $('#region-filter').val();
        loadPickupPoints(selectedRegion);
    };
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});

// Edit pickup point function
function editPickupPoint(pointId) {
    $.ajax({
        url: '../actions/get_pickup_points_action.php?action=get_single&id=' + pointId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var point = response.data;
                $('#edit_point_id').val(point.point_id);
                $('#edit_point_name').val(point.name);
                $('#edit_region').val(point.region);
                $('#edit_city').val(point.city);
                $('#edit_address').val(point.address);
                $('#edit_contact_person').val(point.contact_person);
                $('#edit_contact_phone').val(point.contact_phone);
                $('#edit_operating_hours').val(point.operating_hours);
                $('#edit_is_active').prop('checked', point.is_active == 1);
                $('#editModal').modal('show');
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Failed to load pickup point details.');
        }
    });
}

// Delete pickup point function
function deletePickupPoint(pointId, pointName) {
    if (confirm('Are you sure you want to delete "' + pointName + '"?')) {
        $.ajax({
            url: '../actions/manage_pickup_point_action.php',
            type: 'POST',
            data: { 
                action: 'delete',
                point_id: pointId 
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to delete pickup point. Please try again.');
            }
        });
    }
}

// Toggle status function
function toggleStatus(pointId) {
    $.ajax({
        url: '../actions/manage_pickup_point_action.php',
        type: 'POST',
        data: { 
            action: 'toggle_status',
            point_id: pointId 
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Failed to update status. Please try again.');
        }
    });
}