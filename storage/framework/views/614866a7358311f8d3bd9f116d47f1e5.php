<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #667eea;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .info-section strong {
            color: #667eea;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background-color: #667eea;
            color: white;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #667eea;
        }
        table td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        table tbody tr:hover {
            background-color: #f0f0f0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .position-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #e7f3ff;
            color: #0066cc;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .member-count {
            font-size: 18px;
            color: #667eea;
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo e($club->name); ?></h1>
        <p>Club Members List</p>
    </div>

    <div class="info-section">
        <p>
            <strong>Generated on:</strong> <?php echo e($generatedAt->format('F d, Y \a\t h:i A')); ?><br>
            <strong>Faculty Advisor:</strong> <?php echo e($club->advisors?->name ?? 'Not Assigned'); ?><br>
            <strong class="member-count">Total Members: <?php echo e($members->count()); ?></strong>
        </p>
    </div>

    <?php if($members->count() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 20%;">University ID</th>
                    <th style="width: 25%;">Email</th>
                    <th style="width: 15%;">Position</th>
                    <th style="width: 10%;">Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($member->name); ?></td>
                        <td><?php echo e($member->university_id); ?></td>
                        <td><?php echo e($member->email); ?></td>
                        <td>
                            <?php if($member->pivot->position): ?>
                                <span class="position-badge">
                                    <?php echo e(ucwords(str_replace('_', ' ', $member->pivot->position))); ?>

                                </span>
                            <?php else: ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($member->pivot->joined_at): ?>
                                <?php if(is_string($member->pivot->joined_at)): ?>
                                    <?php echo e(\Carbon\Carbon::parse($member->pivot->joined_at)->format('M d, Y')); ?>

                                <?php else: ?>
                                    <?php echo e($member->pivot->joined_at->format('M d, Y')); ?>

                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 30px;">No members found.</p>
    <?php endif; ?>

    <div class="footer">
        <p>This document was automatically generated by UniClub Hub</p>
        <p style="margin-top: 10px; font-size: 11px;"><?php echo e(config('app.name')); ?> - University Club Management System</p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\ASUS\Documents\CSE470\UniClubHub\resources\views/clubs/members-pdf.blade.php ENDPATH**/ ?>