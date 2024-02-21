package db

import (
	"gorm.io/driver/postgres"
	"gorm.io/gorm"
	"os"
)

func DBinstance() {

}

// host=localhost port=5432 dbname=postgres user=postgres password=xxxxxxx sslmode=prefer connect_timeout=10
var DB *gorm.DB

func ConnectToDb() {
	var err error
	dsn := os.Getenv("DB")
	DB, err = gorm.Open(postgres.Open(dsn), &gorm.Config{})

	if err != nil {
		panic("Failed to connect to db")
	}
}
