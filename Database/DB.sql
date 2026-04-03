-- MySQL

CREATE TABLE Accounts (
	UserID INT AUTO_INCREMENT PRIMARY KEY,
	UserName VARCHAR(50),
	Email VARCHAR(50),
	PasswordHashed VARCHAR(256),
	Verification Boolean,
	Role VARCHAR(50)
);

CREATE TABLE Computers (
	ComputerID INT AUTO_INCREMENT PRIMARY KEY,
	UserID INT,
	CPU VARCHAR(50),
	GPU VARCHAR(50),
	OperatingSystem VARCHAR(50),
	UserComments VARCHAR(100),
	Rank INT,
	TotalBenchmarkingScore INT,
	FOREIGN KEY (UserID) REFERENCES Accounts(UserID) ON DELETE CASCADE
);

CREATE TABLE Benchmarks (
	BenchmarkID INT AUTO_INCREMENT PRIMARY KEY,
	ComputerID INT,
	BenchmarkingTool VARCHAR(50),
	CPUScore INT,
	GPUScore INT,
	TotalScore INT,
	FOREIGN KEY (ComputerID) REFERENCES Computers(ComputerID) ON DELETE CASCADE
);