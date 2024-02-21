package db

import "back/pkg/models"

func SyncDatabase() {
	DB.AutoMigrate(&models.User{})
}
