<!DOCTYPE html>
<html>
<head>
    <title>Event Participant List - {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .event-info {
            margin-bottom: 20px;
        }
        .event-info h1 {
            color: #667eea;
            margin: 0;
            font-size: 24px;
        }
        .event-details {
            margin-top: 10px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .stats {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>UniClub Hub</h1>
        <p>Official Event Participant List</p>
    </div>

    <div class="event-info">
        <h1>{{ $event->title }}</h1>
        <div class="event-details">
            <p><strong>Club:</strong> {{ $event->club->name }}</p>
            <p><strong>Date:</strong> {{ $event->proposed_date->format('F d, Y') }} at {{ $event->proposed_date->format('g:i A') }}</p>
            <p><strong>Venue:</strong> {{ $event->venue ? $event->venue->name : 'TBA' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 40%;">Student Name</th>
                <th style="width: 30%;">University ID</th>
                <th style="width: 20%;">Ticket Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $participant)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $participant->user->name }}</td>
                    <td>{{ $participant->user->university_id }}</td>
                    <td><code>{{ $participant->ticket_code }}</code></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="stats">
        Total Participants: {{ $participants->count() }}
    </div>

    <div class="footer">
        Generated on {{ $generatedAt->format('F d, Y \a\t g:i A') }}<br>
        &copy; {{ date('Y') }} UniClub Hub - University Club Management System
    </div>
</body>
</html>
