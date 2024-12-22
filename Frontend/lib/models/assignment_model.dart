class AssignmentModel {
  int? id;
  String? creatorName;
  String? name;
  String? description;
  int? duration;
  String? startDate;
  String? dueDate;
  String? type;

  AssignmentModel({
    required this.id,
    required this.creatorName,
    required this.name,
    required this.description,
    required this.duration,
    required this.startDate,
    required this.dueDate,
    required this.type,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'creatorName': creatorName,
      'name': name,
      'description': description,
      'duration': duration,
      'startDate': startDate,
      'dueDate': dueDate,
      'type': type,
    };
  }

  AssignmentModel.fromMap(Map<String, dynamic> map) {
    id = map['id'];
    creatorName = map['creatorName'];
    name = map['name'];
    description = map['description'];
    duration = map['duration'];
    startDate = map['startDate'];
    dueDate = map['dueDate'];
    type = map['type'];
  }
}
