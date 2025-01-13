class CourseModel {
  int? id;
  String? name;
  String? description;
  List<String>? categories;
  String? createAt;

  CourseModel({
    required this.id,
    required this.name,
    required this.description,
    required this.createAt,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'categories': categories,
      'createAt': createAt,
    };
  }

  CourseModel.fromMap(Map<String, dynamic> map) {
    id = map['id'];
    name = map['name'];
    description = map['description'];
    categories = map['categories'];
    createAt = map['createAt'];
  }

  CourseModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    name = json['name'];
    description = json['description'];
    categories = json['categories'].cast<String>();
    createAt = json['createAt'];
  }
}
