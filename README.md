# LOG-theGacherguri



## Database Schema

### Database Name
`phpRTCapp` (configurable in `config.php`)

---

### Tables

#### Users Table

| Column | Type | Constraints |
|--------|------|-------------|
| `user_id` | INT | PRIMARY KEY, AUTO_INCREMENT |
| `unique_id` | INT | NOT NULL, UNIQUE |
| `fname` | VARCHAR(255) | NOT NULL |
| `lname` | VARCHAR(255) | NOT NULL |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE |
| `password` | VARCHAR(255) | NOT NULL |
| `img` | VARCHAR(255) | DEFAULT 'default.jpg' |
| `status` | ENUM | DEFAULT 'Offline now' |

#### Messages Table

| Column | Type | Constraints |
|--------|------|-------------|
| `msg_id` | INT | PRIMARY KEY, AUTO_INCREMENT |
| `incoming_msg_id` | INT | NOT NULL |
| `outgoing_msg_id` | INT | NOT NULL |
| `msg` | VARCHAR(1000) | NOT NULL |
