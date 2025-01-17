<?php
class JsonLogger
{
    private $logFile;
    private $maxFileSize; // en bytes
    private $rotateCount;

    public function __construct($filename = 'app_logs.json', $maxFileSize = 1048576, $rotateCount = 5)
    {
        $this->logFile = $filename;
        $this->maxFileSize = $maxFileSize; // 1MB par défaut
        $this->rotateCount = $rotateCount;

        // Crée le fichier s'il n'existe pas
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '[]');
        }
    }

    public function log($action, $data = [])
    {
        // Création de l'entrée de log
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'data' => $data,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'session_id' => session_id() ?? 'no_session'
        ];

        // Lecture du fichier existant
        $logs = json_decode(file_get_contents($this->logFile), true) ?? [];

        // Ajout du nouveau log
        $logs[] = $logEntry;

        // Rotation des logs si nécessaire
        $this->checkRotation();

        // Sauvegarde
        file_put_contents($this->logFile, json_encode($logs, JSON_PRETTY_PRINT));

        return true;
    }

    private function checkRotation()
    {
        if (file_exists($this->logFile) && filesize($this->logFile) > $this->maxFileSize) {
            // Rotation des anciens fichiers
            for ($i = $this->rotateCount; $i > 0; $i--) {
                $oldFile = $this->logFile . '.' . $i;
                $newFile = $this->logFile . '.' . ($i + 1);
                if (file_exists($oldFile)) {
                    rename($oldFile, $newFile);
                }
            }
            // Déplacement du fichier actuel
            rename($this->logFile, $this->logFile . '.1');
            // Création d'un nouveau fichier vide
            file_put_contents($this->logFile, '[]');
        }
    }

    public function search($criteria)
    {
        $logs = json_decode(file_get_contents($this->logFile), true) ?? [];
        return array_filter($logs, function ($log) use ($criteria) {
            foreach ($criteria as $key => $value) {
                if (!isset($log[$key]) || $log[$key] != $value) {
                    return false;
                }
            }
            return true;
        });
    }

    public function getStats()
    {
        $logs = json_decode(file_get_contents($this->logFile), true) ?? [];
        $stats = [
            'total_entries' => count($logs),
            'actions' => [],
            'users' => [],
            'dates' => []
        ];

        foreach ($logs as $log) {
            // Compte des actions
            $stats['actions'][$log['action']] = ($stats['actions'][$log['action']] ?? 0) + 1;
            // Compte par session utilisateur
            $stats['users'][$log['session_id']] = ($stats['users'][$log['session_id']] ?? 0) + 1;
            // Compte par date
            $date = substr($log['timestamp'], 0, 10);
            $stats['dates'][$date] = ($stats['dates'][$date] ?? 0) + 1;
        }

        return $stats;
    }
}
