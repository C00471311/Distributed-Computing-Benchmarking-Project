CREATE DATABASE IF NOT EXISTS benchmark_hub;
USE benchmark_hub;

CREATE TABLE Accounts (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    PasswordHashed VARCHAR(256) NOT NULL,
    Verification BOOLEAN NOT NULL DEFAULT 1,
    Role VARCHAR(50) NOT NULL DEFAULT 'user',
    UNIQUE KEY uq_accounts_username (UserName),
    UNIQUE KEY uq_accounts_email (Email)
);

CREATE TABLE Computers (
    ComputerID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    CPU VARCHAR(100) NOT NULL,
    GPU VARCHAR(100) NOT NULL,
    OperatingSystem VARCHAR(50) NULL,
    UserComments VARCHAR(100) NULL,
    Rank INT NULL,
    TotalBenchmarkingScore INT NOT NULL,
    Status VARCHAR(20) NOT NULL DEFAULT 'pending',
    IsFlagged BOOLEAN NOT NULL DEFAULT 0,
    RejectionReason VARCHAR(255) NULL,
    CreatedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ReviewedAt TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (UserID) REFERENCES Accounts(UserID) ON DELETE CASCADE
);

CREATE TABLE Benchmarks (
    BenchmarkID INT AUTO_INCREMENT PRIMARY KEY,
    ComputerID INT NOT NULL,
    BenchmarkingTool VARCHAR(50) NOT NULL,
    CPUScore INT NULL,
    GPUScore INT NULL,
    TotalScore INT NOT NULL,
    FOREIGN KEY (ComputerID) REFERENCES Computers(ComputerID) ON DELETE CASCADE
);

INSERT INTO Accounts (UserName, Email, PasswordHashed, Verification, Role)
VALUES ('admin', 'admin@example.com', '$2y$10$De.wl2ThWsPzuwfWxkQNNutU6vM0qwpM/vgm.Qi.jLzImYneJZWP2', 1, 'admin');
